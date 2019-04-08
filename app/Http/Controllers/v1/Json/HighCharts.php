<?php

namespace App\Http\Controllers\v1\Json;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use App\v1\MagmaVar;
use App\v1\MagmaVarOptimize;

use App\Traits\v1\DeskripsiGempa;

class HighCharts extends Controller
{

    use DeskripsiGempa;

    protected $series = array();
    protected $categories = array();
    protected $graph;
    protected $default_jumlah = [
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

    protected function setCategories()
    {
        $dates = new \DatePeriod(
            now()->subDays(90),
            new \DateInterval('P1D'),
            now()
        );

        foreach ($dates as $date)
        {
            $categories[] = $date->format('Y-m-d');
        }

        $this->categories = collect($categories);
        return $this;
    }

    protected function getCategories()
    {
        return $this->categories;
    }

    protected function getColor($key)
    {
        switch($key) {
            case 'lts': return $color = '#F44336'; break;
            case 'apl': return $color = '#e91e63'; break;
            case 'gug': return $color = '#1976d2'; break;
            case 'apg': return $color = '#673ab7'; break;
            case 'hbs': return $color = '#3f51b5'; break;
            case 'tre': return $color = '#0d47a1'; break;
            case 'tor': return $color = '#03a9f4'; break;
            case 'lof': return $color = '#006064'; break;
            case 'hyb': return $color = '#009688'; break;
            case 'vtb': return $color = '#8BC34A'; break;
            case 'vta': return $color = '#33691E'; break;
            case 'vlp': return $color = '#827717'; break;
            case 'tel': return $color = '#F57F17'; break;
            case 'trs': return $color = '#FFCA28'; break;
            case 'tej': return $color = '#FFA726'; break;
            case 'dev': return $color = '#ff5722'; break;
            case 'gtb': return $color = '#795548'; break;
            case 'hrm': return $color = '#607d8b'; break;
            case 'mtr': return $color = '#9E9E9E'; break;
        }

    }

    protected function getSeries($key)
    {

        return (object) [
            'count' => $this->graph->sum('var_'.$key),
            'data' => $this->graph->pluck('var_'.$key),
            'color' => $this->getColor($key)
        ];

    }

    protected function getData($ga_code)
    {
        $graph = Cache::remember('v1/home/json/var-show-'.$ga_code, 30, function() use($ga_code) {
            return MagmaVarOptimize::select(
                'var_lts','var_apl','var_gug','var_apg','var_hbs',
                'var_tre','var_tor','var_lof','var_hyb','var_vtb',
                'var_vta','var_vlp','var_tel','var_trs','var_tej',
                'var_dev','var_gtb','var_hrm','var_dpt','ga_code',
                'var_data_date')
            ->where('ga_code',$ga_code)
            ->where('var_perwkt','24 Jam')
            ->whereBetween('var_data_date',[now()->subDays(90)->format('Y-m-d'),now()->format('Y-m-d')])
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

    public function homeGempaThreeMonths(Request $request)
    {
        $id = $request->id;
        $vars = Cache::remember('v1/home/var-show-'.$id, 20, function() use ($id) {
            return MagmaVar::with('gunungapi:ga_code,ga_kab_gapi,ga_prov_gapi,ga_lat_gapi,ga_lon_gapi,ga_elev_gapi,ga_zonearea')
                    ->whereNo($id)
                    ->firstOrFail();
        });

        return [
            'categories' => $this->setCategories()->getCategories(),
            'series' => $this->getData($vars->ga_code)
        ];
    }
}
