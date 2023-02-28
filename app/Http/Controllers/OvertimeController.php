<?php

namespace App\Http\Controllers;

use App\Overtime;
use App\v1\Kantor;
use App\v1\MagmaVarOptimize;
use App\v1\MagmaVen;
use App\Vona;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

class OvertimeController extends Controller
{
    /**
     * Tahun yang saat ini sedang dipilih
     *
     * @var null|string
     */
    protected $year = null;

    /**
     * Bulan yang saat ini sedang dipilih
     *
     * @var null|string
     */
    protected $month = null;

    /**
     * Carbon date
     *
     * @var null|Carbon
     */
    protected $date = null;

    /**
     * List of dates
     *
     * @var Collection
     */
    protected $dates;

    /**
     * Hanya menunjukkan data yang dibuat oleh pengamat
     *
     * @var boolean
     */
    protected $pengamatOnly = false;

    /**
     * Select from Magma Var
     *
     * @var array
     */
    public $magmaVarSelects = [
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
    protected function year(?string $year = null): self
    {
        try {
            $this->year = !is_null($year) ? $year : now()->format('Y');
            Carbon::createFromFormat('Y', $this->year);
        } catch (InvalidArgumentException $th) {
            abort(404);
        }

        if ($this->year < 2023 || ($this->year > (int) (now()->format('Y')))) {
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
     * Undocumented function
     *
     * @return Collection
     */
    public function dates(): Collection
    {
        return $this->dates = collect(
            CarbonPeriod::create($this->betweenDate()[0], $this->betweenDate()[1])->toArray()
        );
    }

    public function holidays(): Collection
    {
        return collect($this->dates)->transform(function (Carbon $date) {
            return $date->isWeekend() ? $date->format('Y-m-d') : null;
        })->filter()->values();
    }

    public function vonaQuery(): Builder
    {
        $vona = Vona::query();
        return $vona->select($this->vonaSelects)->with($this->vonaWith)
            ->where('type', 'REAL')
            ->where('is_sent', 1)
            ->whereBetween('issued', $this->betweenDate());
    }

    public function vonaNight(): Collection
    {
        return $this->vonaQuery()
            ->whereTime('created_at', '>=', '18:00:00')
            ->get()->each->append(['issued_local_date']);
    }

    public function vonaAfterMidnight(): Collection
    {
        return $this->vonaQuery()
            ->whereTime('created_at','<=', '05:00:00')
            ->get()->each->append(['issued_local_date']);
    }

    public function concatVonas(): Collection
    {
        return collect($this->vonaNight()->concat(
            $this->vonaAfterMidnight()
        )->all());
    }

    public function magmaVenQuery(): Builder
    {
        $magmaVen = MagmaVen::query();

        return $magmaVen->select($this->magmaVenSelects)->with($this->magmaVarWith)
            ->whereBetween('erupt_tgl', $this->betweenDate());
    }

    public function magmaVenNight(): EloquentCollection
    {
        return $this->getVens(
            $this->magmaVenQuery()->whereTime('erupt_jam', '>=', '18:00:00')
        );
    }

    public function magmaAfterMidnightt(): EloquentCollection
    {
        return $this->getVens(
            $this->magmaVenQuery()->whereTime('erupt_jam', '<=', '05:00:00')
        );
    }

    public function getVens(Builder $magmaVenQuery): EloquentCollection
    {
        return $magmaVenQuery->get()->each->append(['var_log_local']);
    }

    public function concatMagmaVens(): Collection
    {
        return collect($this->magmaVenNight()->concat(
            $this->magmaAfterMidnightt()
        )->all());
    }

    public function magmaVarsQuery(): Builder
    {
        $magmaVars = MagmaVarOptimize::query();

        return $magmaVars->select($this->magmaVarSelects)->with($this->magmaVarWith)
            ->whereBetween('var_data_date', $this->betweenDate());
    }

    public function magmaVarsNight(): EloquentCollection
    {
        return $this->getVars(
            $this->magmaVarsQuery()->whereTime('var_log', '>=', '18:00:00')
        );
    }

    public function magmaVarsAfterMidnight(): EloquentCollection
    {
        return $this->getVars(
            $this->magmaVarsQuery()->whereTime('var_log', '<=', '07:00:00')
        );
    }

    public function getVars(Builder $magmaVarQuery): EloquentCollection
    {
        return $magmaVarQuery->get()->each->append(['var_log_local']);
    }

    public function concatMagmaVars(): Collection
    {
        return collect($this->magmaVarsNight()->concat(
            $this->magmaVarsAfterMidnight()
        )->all());
    }

    public function groupedVarsByNames(Collection $vars): Collection
    {
        return $vars->groupBy(function ($var) {
            return $var->user->vg_nama ?? 'Guest';
        })->sortKeys();
    }

    public function groupedVensByNames(Collection $vens): Collection
    {
        return $vens->groupBy(function ($vens) {
            return $vens->user->vg_nama ?? 'Guest';
        })->sortKeys();
    }

    public function groupedVonasByNames(Collection $vonas): Collection
    {
        return $vonas->groupBy(function ($vona) {
            return $vona->user->name ?? 'Guest';
        })->sortKeys();
    }

    public function magmaVensOvertime(Collection $vens): Collection
    {
        return $this->groupedVensByNames($vens)->transform(function ($vens, $name) {
            return $this->overtimesCollection(
                $vens->first()['erupt_usr'],
                $name,
                'ven',
                $vens->pluck('erupt_tgl')->unique()->values()
            );
        });
    }

    public function vonasOvertime(Collection $vonas): Collection
    {
        return $this->groupedVonasByNames($vonas)->transform(function ($vonas, $name) {
            return $this->overtimesCollection(
                $vonas->first()['user']['nip'],
                $name,
                'vona',
                $vonas->pluck('issued_local_date')->unique()->values()
            );
        });
    }

    public function magmaVarsHolidays(): Collection
    {
        $holidays = $this->holidays()->toArray();

        $varsHoliday = MagmaVarOptimize::select($this->magmaVarSelects)->with($this->magmaVarWith)
            ->whereIn(DB::raw("DATE(var_log)"), $holidays)->get();

        return $varsHoliday;
    }

    public function magmaVarsOvertime(Collection $vars, string $type = 'var'): Collection
    {
        return $this->groupedVarsByNames($vars)->transform(function ($vars, $name) use ($type) {
            return $this->overtimesCollection(
                $vars->first()['var_nip_pelapor'],
                $name,
                $type,
                $vars->pluck('var_data_date')->unique()->values()
            );
        });
    }

    public function overtimesCollection(string $nip, string $name, string $type, Collection $dates): Collection
    {
        return collect([
            'nip' => $nip,
            'nama' => $name,
            'type' => $type,
            'dates' => $dates,
        ]);
    }

    public function concatOvertimes(): Collection
    {
        $vonasOvertime = $this->vonasOvertime(
            $this->concatVonas()
        )->values();

        $vensOvertime = $this->magmaVensOvertime(
            $this->concatMagmaVens()
        )->values();

        $varsHoliday = $this->magmaVarsOvertime(
            $this->magmaVarsHolidays(), 'holiday'
        )->values();

        $varsOvertime = $this->magmaVarsOvertime(
            $this->concatMagmaVars()
        )->values();

        return $varsOvertime->concat(
                $varsHoliday,
                $vensOvertime,
                $vonasOvertime
        );
    }

    public function groupByConcatOvertimes(Collection $concatOvertimes): Collection
    {
        return $concatOvertimes->groupBy('nama');
    }

    public function transformOvertimes(Collection $groupByConcatOvertimes): Collection
    {
        return $groupByConcatOvertimes->transform(function ($overtimes, $name) {
            $var = $overtimes->where('type', 'var')->first();
            $varHoliday = $overtimes->where('type', 'holiday')->first();
            $ven = $overtimes->where('type', 'ven')->first();
            $vona = $overtimes->where('type', 'vona')->first();

            $varDates = $var ? $var['dates'] : null;
            $varHolidaysDates = $varHoliday ? $varHoliday['dates'] : null;
            $venDates = $ven ? $ven['dates'] : null;
            $vonaDates = $vona ? $vona['dates'] : null;

            $overtimeDates = collect($varDates)
                ->concat(
                    collect($varHolidaysDates),
                    collect($venDates),
                    collect($vonaDates),
                )->unique()
                ->sort()
                ->values();

            $overtimesCount = $overtimeDates->count();

            return [
                'nip' => $overtimes->first()['nip'],
                'name' => $name,
                // 'var' => $varDates,
                // 'holiday' => $varHolidaysDates,
                // 'ven' => $venDates,
                // 'vona' => $vonaDates,
                'overtime' => $overtimeDates,
                'overtime_count' => $overtimesCount,
            ];
        });
    }

    public function overtimes(): Collection
    {
        $vars = $this->transformOvertimes(
            $this->groupByConcatOvertimes(
                $this->concatOvertimes()
            )
        );

        return $this->pengamatOnly ? $this->rejectNonPengamat($vars) : $vars;
    }

    public function cacheIndex(bool $forever = true): Collection
    {
        $pengamatOnly = $this->pengamatOnly ? 'true' : 'false';

        if ($forever) {
            return Cache::tags(['overtime'])->rememberForever("{$this->date->format('Y-m')}-forever-$pengamatOnly", function () {
                return $this->overtimes();
            });
        }

        return Cache::tags(['overtime'])->remember("{$this->date->format('Y-m')}-$pengamatOnly", 60, function () {
            return $this->overtimes();
        });
    }

    /**
     * Menghapus data non pengamat
     *
     * @param Collection $vars
     * @return Collection
     */
    protected function rejectNonPengamat(Collection $vars): Collection
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
    protected function pengamatOnly(Request $request): self
    {
        if ($request->pengamatOnly === 'true') {
            $this->pengamatOnly = true;
        }

        return $this;
    }

    public function datesPeriod()
    {
        return collect($this->dates)->transform(function (Carbon $date) {
            return $date->format('d');
        });
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, string $date = null, bool $flush = false)
    {
        if ($flush) {
            Cache::tags(['overtime'])->flush();
        }

        $this->date($date)->pengamatOnly($request);

        $isCachedForever = $this->date->format('Y-m') === now()->format('Y-m') ? false : true;

        $datesPeriod = $this->datesPeriod();

        return view('overtime.index', [
            'date' => $this->date,
            'pengamat_only' => $this->pengamatOnly,
            'selected_date' => $this->date->formatLocalized('%B %Y'),
            'is_cached' => $isCachedForever,
            'dates_period' => $this->dates,
            'colspan' => $datesPeriod->count(),
            'overtimes' => $this->cacheIndex($isCachedForever)->values(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, string $nip, string $dates = null )
    {
        return $nip;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Overtime  $overtime
     * @return \Illuminate\Http\Response
     */
    public function edit(Overtime $overtime)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Overtime  $overtime
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Overtime $overtime)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Overtime  $overtime
     * @return \Illuminate\Http\Response
     */
    public function destroy(Overtime $overtime)
    {
        //
    }
}
