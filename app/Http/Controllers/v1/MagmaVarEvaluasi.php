<?php

namespace App\Http\Controllers\v1;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use App\Http\Controllers\Controller;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\v1\Gadd;
use App\v1\MagmaVar;
use App\Http\Requests\v1\EvaluasiRequest;

use App\Traits\v1\HighCharts;
use App\Traits\v1\DeskripsiGempa;
use App\Traits\WarnaGempa;
use App\Traits\VisualAsap;

class MagmaVarEvaluasi extends Controller
{

    use WarnaGempa,VisualAsap,DeskripsiGempa,HighCharts;

    protected $widget = array();

    protected $raw_summary = array();

    protected $cache = true;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {  
        $gadds = Cache::remember('chambers/v1/gadds', 240, function () {
            return Gadd::select('ga_code','ga_nama_gapi')
                    ->whereNotIn('ga_code',['TEO'])
                    ->orderBy('ga_nama_gapi','asc')
                    ->get();
        });

        $gempas = collect($this->codes);

        return view('v1.gunungapi.evaluasi.index', compact('gadds','gempas'));
    }

    /**
     * Display a result of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function result(EvaluasiRequest $request)
    {
        $gadd = Gadd::select('ga_code','ga_nama_gapi')
                ->where('ga_code',$request->code)
                ->firstOrFail();

        $this->setCodes($request->gempa)
            ->formatDate($request)
            ->setCategories()
            ->setDefault()
            ->setVars($request->code)
            ->setVarsMerged()
            ->setDataSeries()
            ->setVarsSplice()
            ->setVisualSummary()
            ->setVarSummary()
            ->setWidgetJumlahGempa()
            ->setCache($request);
        
        $data = $this->getResponseData($request);

        return view('v1.gunungapi.evaluasi.result', compact('gadd','data'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        return 'show evaluasi';
    }

    protected function getResponseData($request)
    {
        return [
            'export_chart' => $this->getExportChart(),
            'periode' => $this->getStart()->format('Y-m-d').' hingga '.$this->getEnd()->format('Y-m-d'),
            'periode_report' => $this->getPeriodeReport($request),
            'highcharts' => [
                'var' => [
                    'categories' => $this->getCategories(),
                    'series' => $this->getCache(),
                ],
                'arah_angin' => [
                    'series' => $this->transformSeries('pie','var_arangin')
                ],
                'warna_asap' => [
                    'series' => $this->transformSeries('pie','var_wasap')
                ],
                'tinggi_asap' => [
                    'categories' => $this->getCategories()->slice($this->count)->values(),
                    'series' => $this->transformSeries('column','var_tasap','Tinggi Asap')
                ]
            ],
            'details' => $this->getDetails(),
            'widget' => $this->getWidgetJumlahGempa(),
            'summary' => [
                'visual' => $this->getVisualSummary(),
                'gempa' => $this->clearDeskripsiGempa()->getDeskripsiGempa($this->getVarSummary()),
                'raw' => $this->getRawVarSummary()
            ]
        ];
    }

    protected function setRawVarSummary(Array $summary)
    {
        if ($summary[1]) {
            $this->raw_summary[] = [
                'nama' => $summary[0], 
                'jumlah' => $summary[1],
                'amin' => $summary[2],
                'amax' => $summary[3],
                'spmin' => $summary[4],
                'spmax' => $summary[5],
                'dmin' => $summary[6],
                'dmax' => $summary[7]
            ];
        }

        return $this;
    }

    protected function getRawVarSummary()
    {
        return $this->raw_summary;
    }

    protected function setCache($request)
    {
        $cache = 'chambers/v1/gunungapi/evaluasi:result:'.$request->code.':'.$this->start_str.':'.$this->end_str.':'.implode(':',$request->gempa);

        $cached = $request->cache ? true : false;

        $this->cache_series = $this->cache ? Cache::remember($cache, 120, function () {
            return $this->getDataSeries();
        }) : $this->getDataSeries();

        return $this;
    }

    protected function getCache()
    {
        return $this->cache_series;
    }

    protected function setWidgetJumlahGempa()
    {
        $vars = $this->getVarsSplice();

        if ($vars) {
            $gempas = collect($this->codes);
            $gempas->each(function ($gempa, $key) use ($vars) {
                if ($count = $vars->sum('var_'.$key)) {
                    $this->widget[] = [
                        'name' => $gempa,
                        'count' => $count,
                    ];
                }
            });
    
            return $this;
        }

        return $this;
    }

    protected function getWidgetJumlahGempa()
    {
        return $this->widget;
    }

    protected function formatDate($request)
    {
        $year = Carbon::createFromFormat('Y-m-d', $request->start)->format('Y');
        $month = Carbon::createFromFormat('Y-m-d', $request->start)->format('m');
        $day = Carbon::createFromFormat('Y-m-d', $request->start)->format('d');

        $prev_year = Carbon::createFromFormat('Y-m-d', $request->start)->subYear()->format('Y');

        if ($request->jenis != '2') {
            $start = $month < 7 ? $prev_year.'-07-01' : $year.'-01-01';

            $this->start = Carbon::parse($start);
            $this->start_str = strtotime($this->getStart()->format('Y-m-d'));

            $this->end = $request->jenis == '0' 
                            ? Carbon::parse($request->start)->addDays(14)
                            : Carbon::parse($request->start)->endOfMonth();
            $this->end_str = strtotime($this->getEnd()->format('Y-m-d'));

            $this->days_count = $request->jenis == '0' 
                                    ? 14 
                                    : (int) Carbon::parse($request->start)->daysInMonth;
 
            return $this;
        }

        $this->start = Carbon::parse($request->start);
        $this->start_str = strtotime($this->getStart()->format('Y-m-d'));
        $this->end = Carbon::parse($request->end);
        $this->end_str = strtotime($this->getEnd()->format('Y-m-d'));
        $this->days_count = (int) $this->start->diffInDays($this->end);

        return $this;
    }

    protected function setVarsSplice()
    {
        $vars = $this->getVarsMerged();

        $this->count = $vars->count()-$this->days_count;
        $this->splice_vars = $vars->slice($this->count);
        return $this;
    }

    protected function getVarsSplice()
    {
        return $this->splice_vars;
    }

    protected function getDetails()
    {
        $vars = $this->getVarsSplice();

        foreach ($vars as $var) {

            if (!is_array($var)) {
                $asap = (object) [
                    'wasap' => isset($var->var_wasap) ? $var->var_wasap->toArray() : [],
                    'intasap' => isset($var->var_wasap) ? $var->var_intasap->toArray() : [], 
                    'tasap_min' => $var->var_tasap_min ?? 0,
                    'tasap_max' => $var->var_tasap ?? 0,
                ];
    
                $this->details[] = [
                    'date' => $var->var_data_date->format('Y-m-d'),
                    'gempa' => $this->clearDeskripsiGempa()->getDeskripsiGempa($var),
                    'visual' => $this->clearVisual()
                                ->visibility($var->var_visibility->toArray())
                                ->asap($var->var_asap, $asap)
                                ->cuaca($var->var_cuaca->toArray())
                                ->angin($var->var_kecangin->toArray(),$var->var_arangin->toArray())
                                ->suhu($var->var_suhumin,$var->var_suhumax)
                                ->kelembaban($var->var_kelembabanmin,$var->var_kelembabanmax)
                                ->tekanan($var->var_tekananmin,$var->var_tekananmax)
                                ->getVisual()
                ];
            }

            else {
                $this->details[] = [
                    'date' => $var['var_data_date'],
                    'gempa' => [],
                    'visual' => []
                ];
            }

        }

        return $this->details;
    }

    protected function setVarSummary()
    {
        $vars = $this->getVarsSplice();
        $last = $vars->last();

        $skala = ['I','II','III','IV','V','VI','VII'];

        foreach ($this->getCodes() as $code => $name) {

            $var_code = 'var_'.$code;
            $amin = $var_code.'_amin';
            $amax = $var_code.'_amax';
            $dmin = $var_code.'_dmin';
            $dmax = $var_code.'_dmax';
            $spmin = $var_code.'_spmin';
            $spmax = $var_code.'_spmax';

            $summary[$var_code] = $vars->sum($var_code);
            $summary[$amin] = $vars->where($amin,'>',0)->min($amin);
            $summary[$amax] = $vars->max($amax);

            if (in_array($code,$this->code_sp)) {
                $summary[$spmin] = $vars->where($spmin,'>',0)->min($spmin);
                $summary[$spmax] = $vars->max($spmax);
                $summary[$dmin] = $vars->where($dmin,'>',0)->min($dmin);
                $summary[$dmax] = $vars->max($dmax);

                $this->setRawVarSummary([
                    $name,$summary[$var_code],$summary[$amin],$summary[$amax],
                    $summary[$spmin],$summary[$spmax],$summary[$dmin],$summary[$dmax]
                ]);
            }

            if (in_array($code,$this->code_normal))
            {
                $summary[$dmin] = $vars->where($dmin,'>',0)->min($dmin);
                $summary[$dmax] = $vars->max($dmax);

                $this->setRawVarSummary([
                    $name,$summary[$var_code],$summary[$amin],$summary[$amax],
                    0,0,$summary[$dmin],$summary[$dmax]
                ]);
            }

            if (in_array($code,$this->code_dominan))
            {
                $summary[$var_code.'_adom'] = $vars->max($var_code.'_adom');

                $this->setRawVarSummary([
                    $name,$summary[$var_code],0,$summary[$var_code.'_adom'],
                    0,0,0,0
                ]);

            }

            if (in_array($code,$this->code_luncuran))
            {
                $summary[$dmin] = $vars->where($dmin,'>',0)->min($dmin);
                $summary[$dmax] = $vars->max($dmax);
                $summary[$var_code.'_rmin'] = $vars->where($var_code.'_rmin','>',0)->min($var_code.'_rmin');
                $summary[$var_code.'_rmax'] = $vars->max($var_code.'_rmax');

                $this->setRawVarSummary([
                    $name,$summary[$var_code],$summary[$amin],$summary[$amax],
                    0,0,$summary[$dmin],$summary[$dmax]
                ]);

            }

            if (in_array($code,$this->code_erupsi))
            {
                $summary[$dmin] = $vars->where($dmin,'>',0)->min($dmin);
                $summary[$dmax] = $vars->max($dmax);

                $this->setRawVarSummary([
                    $name,$summary[$var_code],$summary[$amin],$summary[$amax],
                    0,0,$summary[$dmin],$summary[$dmax]
                ]);
            }

            if (in_array($code,$this->code_terasa))
            {
                $summary[$spmin] = $vars->where($spmin,'>',0)->min($spmin);
                $summary[$spmax] = $vars->max($spmax);
                $summary[$dmin] = $vars->where($dmin,'>',0)->min($dmin);
                $summary[$dmax] = $vars->max($dmax);
                $summary[$var_code.'_skalamin'] = implode(',',array_intersect($skala,$vars->pluck('var_trs_skalamin')->flatten()->toArray()));

                $this->setRawVarSummary([
                    $name,$summary[$var_code],$summary[$amin],$summary[$amax],
                    $summary[$spmin],$summary[$spmax],$summary[$dmin],$summary[$dmax]
                ]);
            }

        }

        $merged = array_merge($last->toArray(),$summary);

        $new = new MagmaVar();
        $this->var_summary = $new->fill($merged);

        return $this;
    }
    
    protected function getVarSummary()
    {
        return $this->var_summary;
    }

    protected function implodeVar(String $key) : string
    {
        return implode(',',array_unique($this->getVarsSplice()->pluck($key)->flatten()->toArray()));
    }

    protected function setVisualSummary()
    {
        $vars = $this->getVarsSplice();
        $last = $vars->last()->toArray();

        $new = new MagmaVar();

        $var = $new->fill($last);
        $var = $new->fill([
            'var_visibility' => $this->implodeVar('var_visibility'),
            'var_cuaca' => $this->implodeVar('var_cuaca'),
            'var_curah_hujan' => $vars->max('var_curah_hujan'),
            'var_suhumin' => $vars->where('var_suhumin','>',0)->min('var_suhumin') ?: 0,
            'var_suhumax' => $vars->max('var_suhumax'),
            'var_kelembabanmin' => $vars->where('var_kelembabanmin','>',0)->min('var_kelembabanmin') ?: 0,
            'var_kelembabanmax' => $vars->max('var_kelembabanmax'),
            'var_tekananmin' => $vars->where('var_tekananmin','>',0)->min('var_tekananmin') ?: 0,
            'var_tekananmax' => $vars->max('var_tekananmax'),
            'var_kecangin' => $this->implodeVar('var_kecangin'),
            'var_arangin' => $this->implodeVar('var_arangin'),
            'var_asap' => in_array('Teramati',array_unique($vars->pluck('var_asap')->flatten()->toArray())) ? 'Teramati' : 'Tidak Teramati',
            'var_tasap_min' => $vars->where('var_tasap_min','>',0)->min('var_tasap_min') ?: 0,
            'var_tasap' => $vars->max('var_tasap'),
            'var_wasap' => $this->implodeVar('var_wasap'),
            'var_intasap' => $this->implodeVar('var_intasap'),
            'var_tekasap' => $this->implodeVar('var_tekasap'),
            'var_viskawah' => $this->implodeVar('var_viskawah'),
        ]);

        $asap = (object) [
            'wasap' => isset($var->var_wasap) ? $var->var_wasap->toArray() : [],
            'intasap' => isset($var->var_wasap) ? $var->var_intasap->toArray() : [], 
            'tasap_min' => $var->var_tasap_min,
            'tasap_max' => $var->var_tasap,
        ];

        $this->visual_summary = $var;

        $this->visual_summary = $this->clearVisual()
                    ->visibility($var->var_visibility->toArray())
                    ->asap($var->var_asap, $asap)
                    ->cuaca($var->var_cuaca->toArray())
                    ->angin($var->var_kecangin->toArray(),$var->var_arangin->toArray())
                    ->suhu($var->var_suhumin,$var->var_suhumax)
                    ->kelembaban($var->var_kelembabanmin,$var->var_kelembabanmax)
                    ->tekanan($var->var_tekananmin,$var->var_tekananmax)
                    ->getVisual();

        return $this;
    }

    protected function getVisualSummary()
    {
        return $this->visual_summary;        
    }

    protected function transformSeries($chart, $key, $name = 'Name')
    {

        $var = $this->getVarsSplice();

        if ($chart == 'pie') {

            if ($pluck = $var->pluck($key)) {
                $collection = collect(
                    array_count_values(
                        $pluck->filter(function ($item, $key) {
                            return $item != null;
                        })
                        ->flatten()
                        ->toArray()
                    )
                );
            }

            if ($collection->isNotEmpty() AND $pluck) {
                return array($collection->map(function ($value,$key) {
                    return [
                        'name' => $key,
                        'y' => $value
                    ];
                })->values());
            }

            return [];

        }

        if ($chart == 'column') {

            if ($collection = $var->pluck($key)) {

                if ($collection->sum()) {
                    return array([
                        'name' => $name,
                        'data' => $collection,
                        'color' => '#007fff'
                    ]);
                }
    
                return [];
            }

        }

    }

    protected function getExportChart()
    {
        $data = $this->getDataSeries();

        if (empty($data))
            return 'gempa';

        foreach ($data as $key => $value) {
            $export[] = 'gempa'.$key;
        }

        $export = implode(',',$export);

        return $export;
    }

    protected function getPeriodeReport($request)
    {
        $start = Carbon::parse($request->start);
        $end = $this->getEnd();

        return [
            'start' => $start->formatLocalized('%d %B %Y'),
            'end' => $end->formatLocalized('%d %B %Y'),
            'count' => $start->diffInDays($end)+1
        ];
    }

}