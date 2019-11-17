<?php

namespace App\Http\Controllers\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Carbon;
use App\v1\Gadd;
use App\v1\CompileVar;
use App\v1\MagmaVar;
use App\Traits\v1\DeskripsiGempa;
use App\Traits\VisualAsap;

class CompileVarController extends Controller
{

    use VisualAsap,DeskripsiGempa;

    protected $start_date;

    public function __construct(Request $request)
    {
        ini_set('max_execution_time', 1200);
        $this->start_date = now()->format('Y-m-d');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return $compiles = CompileVar::with('gunungapi:ga_code,ga_nama_gapi')->get();

        return view('v1.gunungapi.compile.index', compact('compiles'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function compile()
    {
        $compiles = CompileVar::whereIsActive(1)->get();

        $vars = MagmaVar::select('ga_code','var_data_date','var_perwkt')
                    ->whereVarPerwkt('6 Jam')
                    ->with('compile')
                    ->whereIn('ga_code',$compiles->pluck('ga_code'))
                    ->groupBy('ga_code')
                    ->get();

        if ($vars) {
            $vars->each(function ($var, $key) {
                $compile = $var->compile;
    
                $start = $compile->start ?: Carbon::parse('2015-05-01 00:00:00');
                $end = $compile->end ?: now()->subDay();
    
                $this->setDateRange($start, $end)
                    ->setVars($var->ga_code)
                    ->compileVars(); 
                
                CompileVar::updateOrCreate([
                    'ga_code' => $var->ga_code
                ],[
                    'start' => now()->subDay()->format('Y-m-d'),
                    'end' => now()->format('Y-m-d')
                ]);

                $compile->touch();
            });
        }

        return CompileVar::all();
    }

    protected function setDateRange($start, $end)
    {
        $this->dates = collect();

        $dates = new \DatePeriod(
            $start,
            new \DateInterval('P1D'),
            $end
        );

        foreach ($dates as $date)
        {
            $this->dates->push(['date' => $date->format('Y-m-d')]);
        }

        return $this;
    }

    protected function setVars($code)
    {
        $vars = MagmaVar::select('ga_code','var_perwkt','var_data_date')
                ->whereGaCode($code)
                ->whereVarPerwkt('24 Jam')
                ->whereBetween('var_data_date',[$this->dates->first(),$this->dates->last()])
                ->get();

        // Tanggal yang belum ada laporan 24 jamnya
        $filtered = $this->dates->whereNotIn('date',$vars->pluck('data_date'))->flatten();

        $filtered = $filtered->isNotEmpty() ? $filtered : collect([ now()->subDay()->format('Y-m-d')]);

        // Ambil laporan 6 jam dari SELURUH tanngal yang belum ada laporan 24 jam nya
        $this->vars_6jam = MagmaVar::whereGaCode($code)
                ->whereVarPerwkt('6 Jam')
                ->orderBy('periode')
                ->whereIn('var_data_date',$filtered)
                ->get();

        $this->dates = $this->dates->whereIn('date',$this->vars_6jam->pluck('data_date'));

        return $this;
    }

    protected function compileVars()
    {
        if ($this->dates->isNotEmpty()) {

            $this->dates->each(function($date, $key) {
                $this->vars = $this->vars_6jam->where('data_date',$date['date']);
    
                if ($this->vars->isNotEmpty()) {
    
                    $this->noticenumber = Carbon::createFromFormat('Y-m-d', $date['date'])->format('Ymd').'2400';

                    $last = $this->vars->last()->replicate();
                    $last->updateOrCreate([
                            'var_noticenumber' => $this->noticenumber,
                        ], array_merge($this->mergedVisual(),$this->mergedGempa())
                    );
                    
                };
    
                $this->start_date = $date['date'];
    
            });

        }

        return $this;
    }

    protected function mergedVisual()
    {
        return [
            'periode' => '00:00-24:00',
            'var_perwkt' => '24 Jam',
            'var_visibility' => implode(',', $this->mergedVisibility()->toArray()),
            'var_cuaca' => implode(',', $this->mergedCuaca()->toArray()),
            'var_curah_hujan' => $this->vars->max('var_curah_hujan'),
            'var_suhumin' => $this->vars->where('var_suhumin','>',0)->min('var_suhumin') ?: 0,
            'var_suhumax' => $this->vars->max('var_suhumax'),
            'var_kelembabanmin' => $this->vars->where('var_kelembabanmin','>',0)->min('var_kelembabanmin') ?: 0,
            'var_kelembabanmax' => $this->vars->max('var_kelembabanmax'),
            'var_tekananmin' => $this->vars->where('var_tekananmin','>',0)->min('var_tekananmin') ?: 0,
            'var_tekananmax' => $this->vars->max('var_tekananmax'),
            'var_kecangin' => implode(',', $this->mergedKecepatanAngin()->toArray()),
            'var_arangin' => implode(',', $this->mergedArahAngin()->toArray()),
            'var_asap' => in_array('Teramati', $this->mergedAsap()->toArray()) ? 'Teramati' : 'Tidak Teramati',
            'var_tasap_min' => $this->vars->where('var_tasap_min','>',0)->min('var_tasap_min') ?: 0,
            'var_tasap' => $this->vars->max('var_tasap'),
            'var_wasap' => implode(',', $this->mergedWarnaAsap()->toArray()),
            'var_intasap' => implode(',', $this->mergedIntensitasAsap()->toArray()),
            'var_tekasap' => implode(',', $this->mergedTekananAsap()->toArray()),
            'var_viskawah' => implode(',', $this->mergedVisualKawah()->toArray()),
        ];
    }

    protected function mergedVisibility()
    {
        return $this->vars->pluck('var_visibility')
                    ->flatten()->unique()->values();
    }

    protected function mergedCuaca()
    {
        return $this->vars->pluck('var_cuaca')
                    ->flatten()->unique()->values();
    }

    protected function mergedKecepatanAngin()
    {
        return $this->vars->pluck('var_kecangin')
                    ->flatten()->unique()->values();
    }

    protected function mergedArahAngin()
    {
        return $this->vars->pluck('var_arangin')
                    ->flatten()->unique()->values();
    }

    protected function mergedAsap()
    {
        return $this->vars->pluck('var_asap')
                    ->flatten()->unique()->values();
    }

    protected function mergedWarnaAsap()
    {
        return $this->vars->pluck('var_wasap')
                    ->flatten()->unique()->values();
    }

    protected function mergedIntensitasAsap()
    {
        return $this->vars->pluck('var_intasap')
                    ->flatten()->unique()->values();
    }

    protected function mergedTekananAsap()
    {
        return $this->vars->pluck('var_tekasap')
                    ->flatten()->unique()->values();
    }

    protected function mergedVisualKawah()
    {
        return $this->vars->pluck('var_viskawah')
                    ->flatten()->unique()->values();
    }

    protected function getCodes()
    {
        return collect($this->codes);
    }

    protected function mergedGempa()
    {
        $vars = $this->vars;

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
            $summary[$amin] = $vars->where($amin,'>',0)->min($amin) ?: 0;
            $summary[$amax] = $vars->max($amax);

            if (in_array($code,$this->code_sp)) {
                $summary[$spmin] = $vars->where($spmin,'>',0)->min($spmin) ?: 0;
                $summary[$spmax] = $vars->max($spmax);
                $summary[$dmin] = $vars->where($dmin,'>',0)->min($dmin) ?: 0;
                $summary[$dmax] = $vars->max($dmax);
            }

            if (in_array($code,$this->code_normal))
            {
                $summary[$dmin] = $vars->where($dmin,'>',0)->min($dmin) ?: 0;
                $summary[$dmax] = $vars->max($dmax);
            }

            if (in_array($code,$this->code_dominan))
            {
                $summary[$var_code.'_adom'] = $vars->max($var_code.'_adom');
            }

            if (in_array($code,$this->code_luncuran))
            {
                $summary[$dmin] = $vars->where($dmin,'>',0)->min($dmin) ?: 0;
                $summary[$dmax] = $vars->max($dmax);
                $summary[$var_code.'_rmin'] = $vars->where($var_code.'_rmin','>',0)->min($var_code.'_rmin') ?: 0;
                $summary[$var_code.'_rmax'] = $vars->max($var_code.'_rmax');
            }

            if (in_array($code,$this->code_erupsi))
            {
                $summary[$dmin] = $vars->where($dmin,'>',0)->min($dmin) ?: 0;
                $summary[$dmax] = $vars->max($dmax);
            }

            if (in_array($code,$this->code_terasa))
            {
                $summary[$spmin] = $vars->where($spmin,'>',0)->min($spmin) ?: 0;
                $summary[$spmax] = $vars->max($spmax);
                $summary[$dmin] = $vars->where($dmin,'>',0)->min($dmin) ?: 0;
                $summary[$dmax] = $vars->max($dmax);
                $summary[$var_code.'_skalamin'] = implode(',',array_intersect($skala,$vars->pluck('var_trs_skalamin')->flatten()->toArray()));
            }

        }

        return $summary;
    }
}
