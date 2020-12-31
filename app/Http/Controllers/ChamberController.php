<?php

namespace App\Http\Controllers;

use App\MagmaVar;
use App\EqLts;
use App\StatistikHome;
use App\v1\Gadd;
use App\v1\GertanCrs;
use App\v1\MagmaVar as OldVar;
use Carbon\CarbonPeriod;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class ChamberController extends Controller
{
    protected $categories;
    protected $visitor;
    protected $series;

    protected function sigertanStatistik()
    {
        $last_crs = GertanCrs::has('tanggapan')
                    ->with('tanggapan')
                    ->where('crs_sta', 'TERBIT')
                    ->whereBetween('crs_lat', [-12, 2])
                    ->whereBetween('crs_lon', [89, 149])
                    ->whereBetween('crs_log', [now()->subDays(60)->format('Y-m-d'), now()->format('Y-m-d')])
                    ->orderBy('crs_log', 'desc')
                    ->first();

        $crses = Cache::remember('v1/home/crs:' . strtotime($last_crs->crs_log), 60, function () {
            return GertanCrs::has('tanggapan')
                ->with('tanggapan')
                ->where('crs_sta', 'TERBIT')
                ->whereBetween('crs_lat', [-12, 2])
                ->whereBetween('crs_lon', [89, 149])
                ->whereBetween('crs_log', [now()->startOfYear()->format('Y-m-d'), now()->endOfYear()->format('Y-m-d')])
                ->orderBy('crs_log', 'desc')
                ->get();
        });

        $crses = $crses->groupBy('date_year_month')->map(function ($crs) {
            return [
                'jumlah_tanggapan' => $crs->count('tanggapan'),
                'meninggal' => $crs->sum('tanggapan.qls_kmd'),
                'luka_luka' => $crs->sum('tanggapan.qls_kll'),
                'rumah_rusak' => $crs->sum('tanggapan.qls_rrk'),
                'rumah_hancur' => $crs->sum('tanggapan.qls_rhc'),
                'rumah_terancam' => $crs->sum('tanggapan.qls_rtr'),
                'bangunan_rusak' => $crs->sum('tanggapan.qls_blr'),
                'bangunan_hancur' => $crs->sum('tanggapan.qls_blh'),
                'bangunan_terancam' => $crs->sum('tanggapan.qls_bla'),
                'lahan_rusak' => $crs->sum('tanggapan.qls_llp'),
                'jalan_rusak' => $crs->sum('tanggapan.qls_pjr'),
            ];
        });

        $period = collect(CarbonPeriod::create(now()->startOfYear(), '1 month', now()->endOfYear())->toArray());
        $merged = $period->mapWithKeys(function ($carbon) {
            return [
                $carbon->format('Y-m') =>
                [
                    'jumlah_tanggapan' => 0,
                    'meninggal' => 0,
                    'luka_luka' => 0,
                    'rumah_rusak' => 0,
                    'rumah_hancur' => 0,
                    'rumah_terancam' => 0,
                    'bangunan_rusak' => 0,
                    'bangunan_hancur' => 0,
                    'bangunan_terancam' => 0,
                    'lahan_rusak' => 0,
                    'jalan_rusak' => 0,
                ],
            ];
        })->merge($crses);

        return $merged->sortKeys();
    }

    public function index()
    {
        $sigertan_table = $this->sigertanStatistik();

        $statistics_chart = Cache::remember('visitor-v2', 10, function () {
            $statistics = StatistikHome::limit(60)->orderBy('date','desc')->get();
            $statistics = $statistics->reverse()->values();
            return $this->setCategories($statistics)->setSeries()->getCharts();
        });
        
        $statistics_sum = Cache::remember('visitor-sum', 10, function () {
            return StatistikHome::sum('hit');
        });
        
        $vars_count = MagmaVar::count();
        $latest = Magmavar::latest()->first();
        $lts_sum = EqLts::sum('jumlah');
        $latest_lts = EqLts::latest()->first();

        $last_var = OldVar::select('no', 'var_log')->orderBy('no', 'desc')->first();
        $gadds = Cache::remember('v1/home/gadd', 120, function () {
            return Gadd::select(
                'ga_code',
                'ga_nama_gapi',
                'ga_kab_gapi',
                'ga_prov_gapi',
                'ga_koter_gapi',
                'ga_elev_gapi',
                'ga_lon_gapi',
                'ga_lat_gapi',
                'ga_status'
            )
            ->whereNotIn('ga_code', ['TEO'])
            ->orderBy('ga_nama_gapi', 'asc')
            ->get();
        });

        $ga_code = $gadds->pluck('ga_code');

        $vars = Cache::remember('chamber/home:' . strtotime($last_var->var_log), 60, function () use ($ga_code) {
            return OldVar::select(DB::raw('t.*'))
                ->from(DB::raw('(SELECT no,ga_code,cu_status,var_data_date,periode,var_perwkt,var_noticenumber,var_nama_pelapor FROM magma_var ORDER BY var_noticenumber DESC) t'))
                ->whereIn('ga_code', $ga_code)
                ->groupBy('t.ga_code')
                ->get();
        });

        $gadds = $gadds->map(function ($gadd, $key) use ($vars) {
            $var = $vars->where('ga_code', $gadd->ga_code)->first();
            $gadd->ga_status = $var->cu_status;
            $gadd->var_no = $var->no;
            return $gadd;
        });

        return view('chambers.index', compact(
            'gadds',
            'vars_count',
            'latest',
            'lts_sum',
            'latest_lts',
            'statistics_chart',
            'statistics_sum',
            'sigertan_table'));
    }

    protected function setCategories($categories)
    {
        $this->visitor = $categories;
        $this->categories = $categories->pluck('date');
        return $this;
    }

    protected function setSeries()
    {
        $this->series[] = [
            'name' => 'Statistik MAGMA v2',
            'data' => $this->visitor->pluck('hit'),
            'color' => '#1b84e7'
        ];

        return $this;
    }

    protected function getCharts()
    {
        return collect([
            'categories' => $this->categories,
            'series' => $this->series
        ]);
    }
}
