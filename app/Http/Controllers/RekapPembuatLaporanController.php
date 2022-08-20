<?php

namespace App\Http\Controllers;

use App\v1\Kantor;
use App\v1\MagmaVarOptimize;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use InvalidArgumentException;
use Illuminate\Support\Str;
use Illuminate\View\View;

class RekapPembuatLaporanController extends Controller
{
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
     * Mendapatkan data dari Magma VAR
     *
     * @return Collection
     */
    protected function getVars(): Collection
    {
        $vars = MagmaVarOptimize::select('var_data_date', 'var_nip_pelapor', 'var_log')
            ->whereBetween('var_data_date', $this->dates())
            ->with('user:vg_nip,vg_nama')
            ->get();

        $vars = $this->pengamatOnly ? $this->rejectNonPengamat($vars) : $vars;

        return $this->rekapLaporan($vars);
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
                return $this->getVars();
            });
        }

        return Cache::remember("rekap-laporan-{$this->year}-{$pengamatOnly}", 60, function () {
            return $this->getVars();
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
            'vars' => $this->year == now()->format('Y') ? $this->cacheVarsForever(false) : $this->cacheVarsForever(),
            'selected_year' => $this->year,
            'years' => $this->years(),
        ]);
    }
}
