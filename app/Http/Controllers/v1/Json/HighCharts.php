<?php

namespace App\Http\Controllers\v1\Json;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;
use App\v1\MagmaVar;
use App\v1\MagmaVarOptimize;

use App\Traits\v1\DeskripsiGempa;
use App\Traits\WarnaGempa;

class HighCharts extends Controller
{

    use WarnaGempa,DeskripsiGempa;

    protected $series = array();
    protected $categories = array();
    protected $graph;
    protected $start;
    protected $start_str;
    protected $end;
    protected $end_str;
    public $default_jumlah = [
        'var_lts' => 0,
        'var_apl' => 0,
        'var_gug' => 0,
        'var_apg' => 0,
        'var_hbs' => 0,
        'var_tre' => 0,
        'var_tor' => 0,
        'var_lof' => 0,
        'var_hyb' => 0,
        'var_vtb' => 0,
        'var_vta' => 0,
        'var_vlp' => 0,
        'var_tel' => 0,
        'var_trs' => 0,
        'var_tej' => 0,
        'var_dev' => 0,
        'var_gtb' => 0,
        'var_hrm' => 0,
        'var_dpt' => 0,
    ];

    public function __construct($start = null, $end = null)
    {
        $this->setDate($start,$end)->setCategories();
    }

    protected function setDate($start = null, $end =  null)
    {
        $this->start = $start ? Carbon::parse($start) : now()->subDays(90);
        $this->start_str = strtotime($this->getStart()->format('Y-m-d'));
        $this->end = $end ? Carbon::parse($end) : now();
        $this->end_str = strtotime($this->getEnd()->format('Y-m-d'));
        return $this;
    }

    protected function setCategories()
    {
        
        $dates = new \DatePeriod(
            $this->getStart(),
            new \DateInterval('P1D'),
            $this->getEnd()
        );

        foreach ($dates as $date)
        {
            $categories[] = $date->format('Y-m-d');
        }

        $this->categories = collect($categories);
        return $this;
    }

    protected function getStart()
    {
        return $this->start;
    }

    protected function getEnd()
    {
        return $this->end;
    }

    protected function getCategories()
    {
        return $this->categories;
    }

    public function getSeries($key)
    {

        return (object) [
            'count' => $this->graph->sum('var_'.$key),
            'data' => $this->graph->pluck('var_'.$key),
            'color' => $this->getColor($key)
        ];

    }

    protected function getData($ga_code)
    {
        $start = strtotime($this->getStart()->format('Y-m-d'));
        $end = strtotime($this->getEnd()->format('Y-m-d'));
        $graph = Cache::remember('v1/home/json/highcharts:var:show:'.$ga_code.':'.$this->start_str.':'.$this->end_str, 30, function() use($ga_code) {
            return MagmaVarOptimize::select(
                'var_lts','var_apl','var_gug','var_apg','var_hbs',
                'var_tre','var_tor','var_lof','var_hyb','var_vtb',
                'var_vta','var_vlp','var_tel','var_trs','var_tej',
                'var_dev','var_gtb','var_hrm','var_dpt','ga_code',
                'var_data_date')
            ->where('ga_code',$ga_code)
            ->where('var_perwkt','24 Jam')
            ->whereBetween('var_data_date',[$this->getStart()->format('Y-m-d'),$this->getEnd()->format('Y-m-d')])
            ->orderBy('var_data_date','asc')
            ->get();
        });

        $categories = $this->getCategories();

        $keyed = $graph->keyBy('var_data_date');

        $this->graph = $categories->flip()->map(function ($item) {
            return $this->default_jumlah;
        })->merge($keyed);
        
        $gempas = collect($this->codes);
        $gempas->each(function ($gempa, $key) {
            $data = $this->getSeries($key);
            if ($data->count) {
                $this->series[] = [
                    'name' => $gempa,
                    'data' => $data->data,
                    'color' => $data->color
                ];
            }
        });

        return $this->series;
    }

    public function getCharts(Request $request)
    {

        $vars = Cache::remember('v1/home/highcharts:var:show:'.$request->id.':'.$this->start_str.':'.$this->end_str, 20, function() use ($request) {
            return MagmaVar::with('gunungapi:ga_code,ga_kab_gapi,ga_prov_gapi,ga_lat_gapi,ga_lon_gapi,ga_elev_gapi,ga_zonearea')
                    ->whereNo($request->id)
                    ->firstOrFail();
        });

        return collect([
            'categories' => $this->getCategories(),
            'series' => $this->getData($vars->ga_code)
        ]);
    }
}
