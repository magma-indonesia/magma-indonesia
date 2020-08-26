<?php

namespace App\Traits\v1;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;
use App\v1\MagmaVar as OldVar;

trait HighCharts
{

    protected $vars;

    protected $series = array();

    protected $default = [
        'var_data_date' => null,
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
        'var_trs_skalamax' => '',
        'var_tel_amin' => 0,
        'var_tej_spmin' => 0,
    ];

    protected function setDefault($var_data_date)
    {
        $default = $this->default;
        $default['var_data_date'] = $var_data_date;
        $model = new OldVar();
        $model->fill($default);

        $this->default_model = $model;

        return $this;
    }

    protected function getDefault()
    {
        return $this->default_model;
    }

    protected function setCodes($kode_gempa)
    {
        $flip_code = collect($kode_gempa)->flip()->toArray();
        $this->codes = array_intersect_key($this->codes,$flip_code);
        return $this;
    }

    protected function getCodes()
    {
        return collect($this->codes);
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
            $this->getEnd()->addDay()
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

    protected function getSeries($key)
    {
        $vars = $this->getVarsMerged();

        return (object) [
            'count' => $vars->sum('var_'.$key),
            'data' => $vars->pluck('var_'.$key),
            'color' => $this->getColor($key)
        ];
    }

    protected function setVars($code)
    {
        $this->vars = Cache::remember('chambers/v1/gunungapi/evaluasi:show:vars:'.$code.':'.$this->start_str.':'.$this->end_str, 30, function() use($code) {
            return OldVar::where('ga_code',$code)
                ->where('var_perwkt','24 Jam')
                ->whereBetween('var_data_date',[$this->getStart()->format('Y-m-d'),$this->getEnd()->format('Y-m-d')])
                ->orderBy('var_data_date','asc')
                ->get();
        });

        return $this;
    }

    protected function getVars()
    {
        return $this->vars;
    }

    protected function setVarsMerged()
    {
        $categories = $this->getCategories();

        $keyed = $this->getVars()->keyBy(function ($item, $key) {
            return $item->var_data_date->format('Y-m-d');
        });

        $this->vars_merged = $categories->flip()->map(function ($item, $key) {
            $model = $this->setDefault($key)->getDefault();
            return $model;
        })->merge($keyed);

        return $this;
    }

    protected function getVarsMerged()
    {
        return $this->vars_merged;
    }

    protected function setDataSeries()
    {
        $gempas = $this->getCodes();
        $gempas->each(function ($gempa, $key) {
            $data = $this->getSeries($key);
            if ($data->count) {
                $this->series[] = [
                    'name' => $gempa,
                    'data' => $data->data,
                    'color' => $data->color,
                    'count' => $data->count
                ];
            }
        });

        return $this;
    }

    protected function getDataSeries()
    {
        return $this->series;
    }
}