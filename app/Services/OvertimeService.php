<?php

namespace App\Services;

use App\User;
use App\v1\Kantor;
use App\v1\MagmaVarOptimize;
use App\v1\MagmaVen;
use App\Vona;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use InvalidArgumentException;

class OvertimeService
{
    /**
     * Tahun yang saat ini sedang dipilih
     *
     * @var null|string
     */
    public $year = null;

    /**
     * Bulan yang saat ini sedang dipilih
     *
     * @var null|string
     */
    public $month = null;

    /**
     * Carbon date
     *
     * @var null|Carbon
     */
    public $date = null;

    /**
     * List of dates
     *
     * @var Collection
     */
    public $dates;

    /**
     * Hanya menunjukkan data yang dibuat oleh pengamat
     *
     * @var boolean
     */
    public $pengamatOnly = false;

    /**
     * Carbon date
     *
     * @var null|string
     */
    public $nip = null;

    /**
     * User
     *
     * @var User
     */
    public $user;

    /**
     * Report in MAGMA
     *
     * @var array
     */
    public $reports = [
        'vars' => 'var',
        'vens' => 'ven',
        'vonas' => 'vona',
    ];

    /**
     * Select from Magma Var
     *
     * @var array
     */
    public $magmaVarSelects = [
        'no',
        'ga_code',
        'cu_status',
        'var_noticenumber',
        'var_data_date',
        'var_log',
        'var_nip_pelapor',
    ];

    /**
     * Select from Magma Ven
     *
     * @var array
     */
    public $magmaVenSelects = [
        'utc',
        'erupt_tgl',
        'erupt_jam',
        'ga_code',
        'erupt_usr',
        'uuid',
    ];

    /**
     * Select from VONA
     *
     * @var array
     */
    public $vonaSelects = [
        'uuid',
        'code_id',
        'type',
        'ash_height',
        'nip_pelapor',
        'issued',
        'created_at'
    ];

    /**
     * Load relationship for Magma Var
     *
     * @var array
     */
    public $magmaVarWith = [
        'user:vg_nip,vg_nama',
        'gunungapi:ga_code,ga_nama_gapi,ga_zonearea',
    ];

    /**
     * Load relationship for Magma Ven
     *
     * @var array
     */
    public $magmaVenWith = [
        'user:vg_nip,vg_nama',
        'gunungapi:ga_code,ga_nama_gapi,ga_zonearea',
    ];

    /**
     * Load relationship for VONA
     *
     * @var array
     */
    public $vonaWith = [
        'user:nip,name',
        'gunungapi:code,name,zonearea',
    ];

    public $translateNip = [
        'var' => 'vg_nip',
        'ven' => 'vg_nip',
        'vona' => 'nip'
    ];

    /**
     * Get start ane end date
     *
     * @param Carbon|null $date
     * @return array
     */
    public function betweenDate(?Carbon $date = null): array
    {
        $date = $this->date ?? $date ?? now();

        return [
            $date->startOfMonth()->format('Y-m-d'),
            $date->endOfMonth()->format('Y-m-d'),
        ];
    }

    /**
     * Year validator
     *
     * @param string|null $year
     * @return self
     */
    public function year(?string $year = null): self
    {
        try {
            $this->year = !is_null($year) ? $year : now()->format('Y');
            Carbon::createFromFormat('Y', $this->year);
        } catch (InvalidArgumentException $th) {
            abort(404);
        }

        if ((int) $this->year < 2023 || ((int) $this->year > (int) (now()->format('Y')))) {
            abort(404);
        }

        return $this;
    }

    /**
     * Make sure date is correct
     *
     * @param string|null $date
     * @return self
     */
    public function date(?string $date = null): self
    {
        try {
            $this->date = !is_null($date) ?
                Carbon::createFromFormat('Y-m', $date) : now();

            $this->dates = $this->dates();
            return $this;
        } catch (\Throwable $th) {
            abort(404);
        }
    }

    /**
     * Set NIP
     *
     * @param string $nip
     * @return self
     */
    public function nip(Request $request, ?string $nip = null): self
    {
        $this->nip = $request->has('nip') ? $request->nip : $nip;

        $this->user = User::where('nip', $this->nip)->firstOrFail();

        return $this;
    }

    /**
     * Flush Cache
     *
     * @param boolean $flush
     * @return self
     */
    public function flush(bool $flush): self
    {
        if ($flush) Cache::tags(['overtime'])->flush();

        return $this;
    }

    /**
     * Get start date and end date in array
     *
     * @return Collection
     */
    public function dates(): Collection
    {
        return $this->dates = collect(
            CarbonPeriod::create($this->betweenDate()[0], $this->betweenDate()[1])->toArray()
        );
    }

    /**
     * Get holidays
     *
     * @return Collection
     */
    public function holidayDates(array $dates = []): Collection
    {
        $dates = collect($this->dates)->isEmpty() ? $this->dates() : $this->dates;

        return collect($dates)->transform(function (Carbon $date) {
            return $date->isWeekend() ? $date->format('Y-m-d') : null;
        })->filter()->values();
    }

    public function reportByType(Collection $overtimes): Collection
    {
        return collect($this->reports)->map(function ($type) use ($overtimes) {

            $ids = $overtimes->where('type', $type)->pluck('ids')->flatten(1);

            switch ($type) {
                case 'var':
                    return MagmaVarOptimize::select($this->magmaVarSelects)
                        ->with($this->magmaVarWith[1])
                        ->whereIn('no', $ids)->get()->each->append(['var_log_local']);
                case 'ven':
                    return MagmaVen::select($this->magmaVenSelects)
                        ->with($this->magmaVenWith[1])
                        ->whereIn('uuid', $ids)->get()->each->append(['erupt_tgl_local']);
                default:
                    return Vona::select($this->vonaSelects)
                        ->with($this->vonaWith[1])
                        ->whereIn('uuid', $ids)->get()->each->append(['created_at_local']);
            }
        });
    }

    /**
     * Get collection of report
     *
     * @param Collection $overtimes
     * @return array
     */
    public function reports(Collection $overtimes): array
    {
        $dateTimes = collect($this->reports)->map(function ($type) use ($overtimes) {
            return $overtimes->where('type', $type)
                ->pluck('dates')->flatten(1)->sort()->values();
        });

        return [
            'reports' => $this->reportByType($overtimes),
            'group_by_type_and_dates' => $dateTimes->map(function ($report) {
                return $report->groupBy(function ($dateTime) {
                    return $dateTime->format('Y-m-d');
                })->map(function ($dateTimes) {
                    return $dateTimes->map(function ($dateTime) {
                        return $dateTime->format('H:i:s');
                    })->unique()->values();
                });
            }),
            'unique_dates' => $dateTimes->flatten(1)
                ->map(function ($dateTime) {
                    return $dateTime->format('Y-m-d');
                })
                ->unique()
                ->sort()
                ->values()
        ];
    }

    /**
     * Get overtimes
     *
     * @return Collection
     */
    public function overtimes(): Collection
    {
        $collection = collect([
            $this->vars(),
            $this->vonas(),
            $this->vens(),
        ])->flatten(1)->groupBy('nama')->transform(function ($overtimes, $name) {
            $reports = $this->reports($overtimes);

            return collect([
                'nama' => $name,
                'nip' => $overtimes->first()['nip'],
                'overtimes' => $reports['unique_dates'],
                'overtimes_count' => $reports['unique_dates']->count(),
            ])->merge([
                'group_by_type_and_dates' => $reports['group_by_type_and_dates'],
                'reports' => $reports['reports'],
            ]);
        });

        return $this->pengamatOnly ? $this->rejectNonPengamat($collection) : $collection;
    }

    /**
     * Check if VARS is cached
     *
     * @return boolean
     */
    public function isCachedForever(): bool
    {
        return ($this->date->format('Y-m')) !== (now()->format('Y-m')) ?
            true : false;
    }

    /**
     * Cache Index response
     *
     * @param boolean $forever
     * @return Collection
     */
    public function cacheIndex(): Collection
    {
        $pengamatOnly = $this->pengamatOnly ? 'true' : 'false';

        if ($this->isCachedForever()) {
            return Cache::tags(['overtime'])->rememberForever("{$this->date->format('Y-m')}-forever-$pengamatOnly", function () {
                return $this->overtimes();
            });
        }

        return Cache::tags(['overtime'])->remember("{$this->date->format('Y-m')}-$pengamatOnly", 60, function () {
            return $this->overtimes();
        });
    }

    /**
     * Generate cache name
     *
     * @return string
     */
    public function cacheShowName(): string
    {
        return $this->isCachedForever() ?
            "{$this->date->format('Y-m')}-{$this->nip}-forever" :
            "{$this->date->format('Y-m')}-{$this->nip}";
    }

    /**
     * Cache by NIP
     *
     * @return Collection
     */
    public function cacheShow(?bool $isCached = null): Collection
    {
        if ($isCached) {
            return Cache::tags(['overtime'])->rememberForever($this->cacheShowName(), function () {
                return $this->overtimes();
            });
        }

        return Cache::tags(['overtime'])->remember($this->cacheShowName(), 60, function () {
            return $this->overtimes();
        });
    }

    /**
     * Menghapus data non pengamat
     *
     * @param Collection $vars
     * @return Collection
     */
    public function rejectNonPengamat(Collection $vars): Collection
    {
        $pengamats = Cache::remember('pengamat-gunung-api', 60 * 24, function () {
            return Kantor::whereNotIn('obscode', [
                'PVG',
                'PAG',
                'PSG',
                'PSM',
                'BTK',
                'BGL',
            ])->with('user:vg_nip')->pluck('vg_nip')->toArray();
        });

        return $vars->whereIn('nip', $pengamats);
    }

    /**
     * Assigning untuk filter pengamat
     *
     * @param Request $request
     * @return self
     */
    public function pengamatOnly(Request $request): self
    {
        if ($request->pengamatOnly === 'true') {
            $this->pengamatOnly = true;
        }

        return $this;
    }

    /**
     * Dates period
     *
     * @return Collection
     */
    public function datesPeriod(): Collection
    {
        return collect($this->dates)->map(function (Carbon $date) {
            return $date->format('d');
        });
    }

    /**
     * Disable order for datatable
     *
     * @return Collection
     */
    public function disableOrder(): Collection
    {
        $disableOrder = $this->dates->map(function ($order, $index) {
            return [
                'orderable' => false,
                'targets' => $index + 2,
            ];
        });

        return $disableOrder;
    }

    /**
     * Get VARS-query
     *
     * @return Builder
     */
    public function varsQuery(string $type = 'var'): Builder
    {
        $magmaVars = MagmaVarOptimize::query();

        $type === 'holiday' ?
            $magmaVars->holiday($this->holidayDates()->toArray()) :
            $magmaVars->overtime($this->betweenDate());

        return $magmaVars->select($this->magmaVarSelects)
            ->with($this->magmaVarWith)
            ->nip($this->nip);
    }

    /**
     * Get MAGMA-VARS
     *
     * @return Collection
     */
    public function vars(): Collection
    {
        return collect(['var', 'holiday'])->transform(function ($type) {
            return $this->varsQuery($type)->get()->each->append(['var_log_local'])->when($type === 'var', function ($vars) {
                return $vars->reject(function ($var) {
                    return ($var->var_log_local->format('H:i:s') > '06:00:00') &&
                        ($var->var_log_local->format('H:i:s') < '18:00:00');
                });
            });
        })->flatten(2)->groupBy(function ($var) {
            return $var->user->vg_nama ?? 'Guest';
        })->transform(function ($vars, $name) {
            return [
                'nip' => $vars->first()['var_nip_pelapor'],
                'nama' => $name,
                'type' => 'var',
                'dates' => $vars->pluck('var_log_local')->values(),
                'ids' => $vars->pluck('no'),
            ];
        })->sortKeys();
    }

    /**
     * Query for VONA
     *
     * @return Builder
     */
    public function vonasQuery(string $type = 'vona'): Builder
    {
        $vona = Vona::query();

        $type === 'holiday' ?
            $vona->holiday($this->holidayDates()->toArray()) :
            $vona->overtime($this->betweenDate());

        return $vona->select($this->vonaSelects)
            ->with($this->vonaWith)
            ->nip($this->nip);
    }

    /**
     * Get VONA
     *
     * @param string $type
     * @return Collection
     */
    public function vonas(): Collection
    {
        return collect(['var', 'holiday'])->transform(function ($type) {
            return $this->vonasQuery($type)->get()->each->append(['created_at_local'])->when($type === 'vona', function ($vonas) {
                return $vonas->reject(function ($vona) {
                    return ($vona->created_at_local->format('H:i:s') > '06:00:00') &&
                        ($vona->created_at_local->format('H:i:s') < '18:00:00');
                });
            });
        })->flatten(2)->groupBy(function ($vona) {
            return $vona->user->name ?? 'Guest';
        })->transform(function ($vonas, $name) {
            return [
                'nip' => $vonas->first()['user']['nip'],
                'nama' => $name,
                'type' => 'vona',
                'dates' => $vonas->pluck('created_at_local')->values(),
                'ids' => $vonas->pluck('uuid'),
            ];
        })->sortKeys();
    }

    /**
     * Query VENS
     *
     * @param string $type
     * @return Builder
     */
    public function vensQuery(string $type = 'ven'): Builder
    {
        $magmaVens = MagmaVen::query();

        $type === 'holiday' ?
            $magmaVens->holiday($this->holidayDates()->toArray()) :
            $magmaVens->overtime($this->betweenDate());

        return $magmaVens->select($this->magmaVenSelects)
            ->with($this->magmaVenWith)
            ->overtime($this->betweenDate())
            ->nip($this->nip);
    }

    /**
     * Undocumented function
     *
     * @return Collection
     */
    public function vens(): Collection
    {
        return collect(['ven', 'holiday'])->transform(function ($type) {
            return $this->vensQuery($type)->get()->each->append(['erupt_tgl_local']);
        })->flatten(2)->groupBy(function ($ven) {
            return $ven->user->vg_nama ?? 'Guest';
        })->transform(function ($vens, $name) {
            return [
                'nip' => $vens->first()['erupt_usr'],
                'nama' => $name,
                'type' => 'ven',
                'dates' => $vens->pluck('erupt_tgl_local')->values(),
                'ids' => $vens->pluck('uuid'),
            ];
        })->sortKeys();
    }
}