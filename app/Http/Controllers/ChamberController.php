<?php

namespace App\Http\Controllers;

use App\MagmaVar;
use App\EqLts;
use App\StatistikHome;
use App\v1\Gadd;
use App\v1\MagmaVar as OldVar;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class ChamberController extends Controller
{
    protected $categories;
    protected $visitor;
    protected $series;

    public function index()
    {
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

        $vars = Cache::remember('v1/home/var:' . strtotime($last_var->var_log), 60, function () use ($ga_code) {
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
            'statistics_sum'));
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
