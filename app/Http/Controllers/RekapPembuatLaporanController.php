<?php

namespace App\Http\Controllers;

use App\v1\Gadd;
use App\v1\Kantor;
use App\v1\MagmaVarOptimize;
use App\v1\User;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\URL;
use InvalidArgumentException;
use Illuminate\Support\Str;
use Illuminate\View\View;

class RekapPembuatLaporanController extends Controller
{
    /**
     * User MAGMA v1
     *
     * @var User
     */
    protected $user;

    /**
     * Hanya menunjukkan data yang dibuat oleh pengamat
     *
     * @var boolean
     */
    protected $pengamatOnly = false;

    /**
     * Tahun yang saat ini sedang dipilih
     *
     * @var null|string
     */
    protected $year = null;

    /**
     * Slug gunung api
     *
     * @var Gadd
     */
    protected $gunungApi;

    protected function gunungApi(string $slug): self
    {
        $this->gunungApi = Gadd::where('slug', $slug)->firstOrFail();
        return $this;
    }

    /**
     * Assigning User property
     *
     * @param string $nip
     * @return self
     */
    protected function user(string $nip): self
    {
        $this->user = User::where('vg_nip', $nip)->firstOrFail();
        return $this;
    }

    /**
     * Membuat range tahun
     *
     * @return Collection
     */
    protected function years(): Collection
    {
        return collect(CarbonPeriod::create(
            Carbon::createFromDate('2015')->startOfYear(),
            '1 year',
            now()->endOfYear(),
        ));
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

    /**
     * Undocumented function
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

        return $this;
    }

    /**
     * Mendapatkan range tanggal pencarian
     *
     * @return array
     */
    protected function dates(): array
    {
        return [
            Carbon::createFromFormat('Y', $this->year)->startOfYear()->format('Y-m-d'),
            Carbon::createFromFormat('Y', $this->year)->endOfYear()->format('Y-m-d'),
        ];
    }

    /**
     * Menghitung laporan tiap bulannya untuk tiap staff
     *
     * @param Collection $vars
     * @param string $month
     * @return integer
     */
    protected function countLaporanPerBulan(Collection $vars, string $month): int
    {
        return $vars->filter(function ($var) use($month) {
                return Str::startsWith($var->var_data_date, "{$this->year}-{$month}");
        })->count();
    }

    protected function getTimePeriod(string $periode): array
    {
        switch ($periode) {
            case '00:00-24:00':
                return [
                    'start' => '00:00:00',
                    'end' => '23:59:59'
                ];
            case '00:00-06:00':
                return [
                    'start' => '00:00:00',
                    'end' => '06:00:00'
                ];
            case '06:00-12:00':
                return [
                    'start' => '06:00:00',
                    'end' => '12:00:00'
                ];
            case '12:00-18:00':
                return [
                    'start' => '12:00:00',
                    'end' => '18:00:00'
                ];
            case '18:00-24:00':
                return [
                    'start' => '18:00:00',
                    'end' => '23:59:59'
                ];
        }
    }

    protected function getStartDateFroCalendar(string $var_data_date, string $periode): array
    {
        return [
            'start' => Carbon::createFromFormat(
                'Y-m-d H:i:s',
                $var_data_date . " " . $this->getTimePeriod($periode)['start'],
                'UTC')->toISOString(),
            'end' => Carbon::createFromFormat(
                'Y-m-d H:i:s',
                $var_data_date . " " . $this->getTimePeriod($periode)['end'],
                'UTC')->toISOString(),
        ];
    }

    protected function calendar(Collection $vars)
    {
        $vars->transform(function ($var) {
            return [
                'title' => $var->user->vg_nama,
                'date' => $this->getStartDateFroCalendar($var->var_data_date, $var->periode),
                'all_day' => false,
            ];
        });

        return $vars;
    }

    /**
     * Menghitung rekap laporan berdsarkan identitas NIP
     *
     * @param Collection $vars
     * @return Collection
     */
    protected function rekapLaporanByNip(Collection $vars): Collection
    {
        $vars->transform(function ($var) {
            return [
                'nip' => $this->user->vg_nip,
                'nama' => $this->user->vg_nama,
                'gunung_api' => $var->gunungapi->ga_nama_gapi,
                'slug' => $var->gunungapi->slug,
                'tanggal_laporan' => Carbon::createFromFormat('Y-m-d', $var->var_data_date),
                'jenis_periode_laporan' => $var->var_perwkt,
                'periode_laporan' => $var->periode,
                'dibuat_pada' => Carbon::createFromFormat('d/m/Y H:i:s', $var->var_issued),
                'time_zone' => $var->gunungapi->ga_zonearea,
                'link' => URL::route('chambers.v1.gunungapi.laporan.show', ['id' => $var->no]),
            ];
        });

        return $vars;
    }

    protected function rekapLaporanByGunungApi(Collection $vars)
    {
        return [
            'users' => $this->rekapLaporan($vars),
            'calendar' => $this->calendar($vars),
        ];
    }

    /**
     * Melakukan rekapitulasi laporan
     *
     * @param Collection $vars
     * @return Collection
     */
    protected function rekapLaporan(Collection $vars): Collection
    {
        $groupedByNames = $vars->groupBy(function ($var) {
            return $var->user->vg_nama;
        })->sortKeys();

        $groupedByNames->transform(function ($vars, $name) {
            return [
                'nip' => $vars->first()['var_nip_pelapor'],
                'nama' => $name,
                'total_laporan_dibuat' => $vars->count(),
                'jumlah_laporan_per_bulan' => [
                    'januari' => $this->countLaporanPerBulan($vars, '01'),
                    'februari' => $this->countLaporanPerBulan($vars, '02'),
                    'maret' => $this->countLaporanPerBulan($vars, '03'),
                    'april' => $this->countLaporanPerBulan($vars, '04'),
                    'mei' => $this->countLaporanPerBulan($vars, '05'),
                    'juni' => $this->countLaporanPerBulan($vars, '06'),
                    'juli' => $this->countLaporanPerBulan($vars, '07'),
                    'agustus' => $this->countLaporanPerBulan($vars, '08'),
                    'september' => $this->countLaporanPerBulan($vars, '09'),
                    'oktober' => $this->countLaporanPerBulan($vars, '10'),
                    'november' => $this->countLaporanPerBulan($vars, '11'),
                    'desember' => $this->countLaporanPerBulan($vars, '12'),
                ]
            ];
        });

        return $groupedByNames->values();
    }

    /**
     * Menghapus data non pengamat
     *
     * @param Collection $vars
     * @return Collection
     */
    protected function rejectNonPengamat(Collection $vars): Collection
    {
        $pengamats = Cache::remember('pengamat-gunung-api', 60*24, function () {
            return Kantor::whereNotIn('obscode', [
                'PVG',
                'PAG',
                'PSG',
                'PSM',
                'BTK',
                'BGL',
            ])->with('user:vg_nip')->pluck('vg_nip')->toArray();
        });

        return $vars->whereIn('var_nip_pelapor', $pengamats);
    }

    /**
     * Mendapatkan semua laporan VARS berdasarkan NIP
     *
     * @return Collection
     */
    protected function getVarsByNip(): Collection
    {
        $vars = MagmaVarOptimize::select('no', 'ga_code','var_data_date', 'var_perwkt', 'periode', 'var_nip_pelapor', 'var_noticenumber','var_issued')
            ->with('gunungapi:ga_code,ga_nama_gapi,ga_zonearea,slug')
            ->where('var_nip_pelapor', $this->user->vg_nip)
            ->whereBetween('var_data_date', $this->dates())
            ->orderBy('var_data_date', 'desc')
            ->get();

        return $vars;
    }

    /**
     * Mendapatkan data dari Magma VAR
     *
     * @return Collection
     */
    protected function getVars(): Collection
    {
        $vars = MagmaVarOptimize::select('var_data_date', 'var_nip_pelapor')
            ->whereBetween('var_data_date', $this->dates())
            ->with('user:vg_nip,vg_nama')
            ->get();

        $vars = $this->pengamatOnly ? $this->rejectNonPengamat($vars) : $vars;

        return $vars;
    }

    protected function getIndexVarsByGunungApi()
    {
        $vars = MagmaVarOptimize::select('var_data_date', 'var_nip_pelapor')
            ->whereBetween('var_data_date', $this->dates())
            ->with('user:vg_nip,vg_nama')
            ->get();
    }

    protected function getShowVarsByGunungApi()
    {
        $vars = MagmaVarOptimize::select('no', 'ga_code','var_data_date', 'var_perwkt', 'periode', 'var_nip_pelapor', 'var_noticenumber','var_issued')
            ->with('gunungapi:ga_code,ga_nama_gapi,ga_zonearea')
            ->with('user:vg_nip,vg_nama')
            ->where('ga_code', $this->gunungApi->ga_code)
            ->whereBetween('var_data_date', $this->dates())
            ->orderBy('var_data_date', 'asc')
            ->get();

        return $vars;
    }

    /**
     * Cache VAR by NIP
     *
     * @param boolean $forever
     * @return Collection
     */
    protected function cacheVarsByNipForever(bool $forever = true): Collection
    {
        if ($forever) {
            return Cache::rememberForever("rekap-laporan-forever-{$this->year}-{$this->user->vg_nip}", function () {
                return $this->rekapLaporanByNip(
                    $this->getVarsByNip()
                );
            });
        }

        return Cache::remember("rekap-laporan-{$this->year}-{$this->user->vg_nip}", 60, function () {
            return $this->rekapLaporanByNip(
                $this->getVarsByNip()
            );
        });
    }

    /**
     * Cache hasil rekapan tiap tahun
     *
     * @param boolean $forever
     * @return Collection
     */
    protected function cacheVarsForever(bool $forever = true): Collection
    {
        $pengamatOnly = $this->pengamatOnly ? 'true' : 'false';

        if ($forever) {
            return Cache::rememberForever("rekap-laporan-forever-{$this->year}-{$pengamatOnly}", function () {
                return $this->rekapLaporan($this->getVars());
            });
        }

        return Cache::remember("rekap-laporan-{$this->year}-{$pengamatOnly}", 60, function () {
            return $this->rekapLaporan($this->getVars());
        });
    }

    protected function cacheIndexVarsGunungApiForever(bool $forever = true)
    {
        return $this->getIndexVarsByGunungApi();
    }

    protected function cacheShowVarsGunungApiForever(bool $forever = true)
    {
        if ($forever) {
            return Cache::rememberForever("rekap-laporan-show-gunungpi-forever-{$this->year}-{$this->gunungApi->slug}", function () {
                return $this->rekapLaporanByGunungApi(
                    $this->getShowVarsByGunungApi());
            });
        }

        return Cache::remember("rekap-laporan-show-gunungpi-{$this->year}-{$this->gunungApi->slug}", 60, function () {
            return $this->rekapLaporanByGunungApi(
                $this->getShowVarsByGunungApi()
            );
        });
    }

    /**
     * Menampilkan hasil rekapan laporan VAR
     *
     * @param Request $request
     * @param string $year
     * @return View
     */
    public function index(Request $request, string $year = null): View
    {
        $this->year($year)->pengamatOnly($request);

        return view('rekap-laporan.index', [
            'vars' => $this->year == now()->format('Y') ?
                $this->cacheVarsForever(false) : $this->cacheVarsForever(),
            'selected_year' => $this->year,
            'years' => $this->years(),
        ]);
    }

    /**
     * Menampilak hasil rekapan laporan VAR berdasarkan NIP
     *
     * @param Request $request
     * @param string $year
     * @param string $nip
     * @return View
     */
    public function showByNip(Request $request, string $year, string $nip): View
    {
        $this->year($year)->user($nip);

        return view('rekap-laporan.show-by-nip', [
            'user' => $this->user,
            'selected_year' => $this->year,
            'years' => $this->years(),
            'vars' => $this->year == now()->format('Y') ?
                $this->cacheVarsByNipForever(false) : $this->cacheVarsByNipForever(),
        ]);
    }

    public function indexByGunungApi()
    {
        return 'ok';
    }

    public function showByGunungApi(Request $request, string $year, string $slug)
    {
        $this->year($year)->gunungApi($slug);

        return view('rekap-laporan.show-by-gunungapi', [
            'selected_year' => $this->year,
            'years' => $this->years(),
            'gadd' => $this->gunungApi,
            'vars' => $this->year == now()->format('Y') ?
                $this->cacheShowVarsGunungApiForever(false) : $this->cacheShowVarsGunungApiForever(),
        ]);
    }
}
