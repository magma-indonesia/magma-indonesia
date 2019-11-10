<?php

namespace App\Http\Controllers;

use App\ResumeHarian;
use App\BencanaGeologi;
use App\BencanaGeologiPendahuluan as Pendahuluan;
use App\StatistikResumeHarian;
use App\v1\MagmaVar;
use App\Gadd;
use Illuminate\Http\Request;
use App\Traits\v1\DeskripsiGempa;
use App\Traits\VisualAsap;
use Illuminate\Support\Carbon;

class ResumeHarianController extends Controller
{

    use VisualAsap,DeskripsiGempa;

    protected $resultText;

    protected $widget;

    protected function getVisualDeskripsi($var)
    {
        if ($var) {
            $asap = (object) [
                'wasap' => isset($var->var_wasap) ? $var->var_wasap->toArray() : [],
                'intasap' => isset($var->var_wasap) ? $var->var_intasap->toArray() : [], 
                'tasap_min' => $var->var_tasap_min,
                'tasap_max' => $var->var_tasap,
            ];
    
            $visual_deskripsi = $this->clearVisual()
                        ->visibility($var->var_visibility->toArray())
                        ->asap($var->var_asap, $asap)
                        ->cuaca($var->var_cuaca->toArray())
                        ->angin($var->var_kecangin->toArray(),$var->var_arangin->toArray())
                        ->suhu($var->var_suhumin,$var->var_suhumax)
                        ->kelembaban($var->var_kelembabanmin,$var->var_kelembabanmax)
                        ->tekanan($var->var_tekananmin,$var->var_tekananmax)
                        ->getVisual();
            
            return $visual_deskripsi;
        }

        return '*Belum ada laporan 24 Jam yang masuk.*';
    }

    protected function getInformasiLetusan($var)
    {
        $var = MagmaVar::select(
                        'var_lts',
                        'var_lts_amin',
                        'var_lts_amax',
                        'var_lts_dmin',
                        'var_lts_dmax',
                        'var_lts_tmin',
                        'var_lts_tmax',
                        'var_lts_wasap',
                        'ga_code',
                        'var_data_date',
                        'var_perwkt'
                    )
                    ->where('var_lts','>',0)
                    ->whereGaCode($var->ga_code)
                    ->where('var_data_date','<=',$this->date)
                    ->whereVarPerwkt('24 Jam')
                    ->orderByDesc('var_data_date')
                    ->first();

        if ($var) {

            $tinggiErupsi = $var->var_lts_tmax > 0 ? ' menghasilkan tinggi kolom erupsi '.$var->var_lts_tmax.' m.' : 0;

            $tinggiErupsi = $var->var_lts_tmax == 0 ? ' dengan tinggi kolom erupsi tidak teramati.' : $tinggiErupsi;
    
            $warnaErupsi = $var->var_lts_wasap ? ' Warna kolom abu teramati '.str_replace_last(', ',' hingga ',implode(', ',$var->var_lts_wasap->toArray())).'.' : '';

            $letusan = ' Letusan terakhir terjadi pada tanggal '.$var->var_data_date->formatLocalized('%d %B %Y').$tinggiErupsi.$warnaErupsi;

            return collect([$letusan]);
        };

        return collect([]);
    }

    protected function setJumlahGempa($var)
    {
        $this->widget = collect();

        if ($var) {
            collect($this->codes)->each(function ($gempa, $key) use ($var) {
                if ($count = $var->{'var_'.$key}) {
                    $this->widget->push(collect([
                        'name' => $gempa,
                        'key' => $key,
                        'count' => $count,
                        'amplitudo' => $var->{'var_'.$key.'_amin'} == $var->{'var_'.$key.'_amax'} ? $var->{'var_'.$key.'_amin'} : $var->{'var_'.$key.'_amin'}.'-'.$var->{'var_'.$key.'_amax'},
                        'adom' => $var->{'var_'.$key.'_adom'},
                    ]));
                }
            });

            if ($this->widget->isEmpty())
                $this->widget = collect(['count' => 0]);
        }

        return $this;
    }

    protected function toText()
    {
        if ($this->widget->isNotEmpty()) {

            if ($this->widget->sum('count') == 0)
                return collect(['- Kegempaan nihil']);

            $texts = $this->widget->map(function ($item, $key) {
                if ($item['name'] == 'Tremor Menerus') {
                    return '- Tremor Menerus, amplitudo '.$item['amplitudo'].' mm (dominan '.$item['adom'].' mm)';
                }
                return '- '.$item['count'].' kali gempa '.$item['name'];
            });

            return $texts;
        }

        return collect([]);
    }

    protected function getNewVar($ga_code)
    {
        $var = MagmaVar::whereGaCode($ga_code)
                ->whereVarDataDate($this->date)
                ->wherePeriode('00:00-06:00')
                ->first();

        return $this->setJumlahGempa($var)->toText();
    }

    protected function generateContents()
    {
        $this->bencanas->each(function ($bencana, $key) {
            $this->resultText[] = [
                'gunungapi' => 'Gunungapi '.$bencana->gunungapi->name.' ('.$bencana->gunungapi->province.')',
                'pendahuluan' => $bencana->pendahuluan->pendahuluan,
                'visual' => $this->getVisualDeskripsi($bencana->magma_var),
                'gempa' => $this->setJumlahGempa($bencana->magma_var)->toText()->isNotEmpty() ? $this->setJumlahGempa($bencana->magma_var)->toText() : collect(['Belum ada laporan 24 Jam yang masuk.']),
                'sometimes' => $bencana->gunungapi->code == 'AGU' ? $this->getNewVar($bencana->gunungapi->code) : collect([]),
                'letusan' => $bencana->magma_var ? $this->getInformasiLetusan($bencana->magma_var) : collect([]),
                'rekomendasi' => $bencana->magma_var->var_rekom ?? 'Belum ada laporan 24 Jam yang masuk.',
            ];
        });

        return $this;
    }

    protected function getContents()
    {
        $contents = collect($this->resultText)->map(function ($content, $key) {

            $gunungapi = $content['gunungapi'];
            $pendahuluan = $content['pendahuluan'];
            $visual = $content['visual'];
            $letusan = $content['letusan']->isNotEmpty() ? $content['letusan']->first() : '';
            $gempa = $content['gempa']->isNotEmpty() ? implode("\n", $content['gempa']->toArray()) : '*Belum ada laporan 24 Jam yang masuk.*';
            $sometimes = $content['sometimes']->isNotEmpty() ? "\n\nMelalui rekaman seismograf pada ".$this->date_localized." (Pukul. 00:00-06:00 WITA) tercatat:\n".implode("\n", $content['sometimes']->toArray()) : '';
            $rekomendasi = $content['rekomendasi'] ?? '*Belum ada laporan 24 Jam yang masuk.*';

            $temp = '*'.$gunungapi."*\n\n".$pendahuluan.$letusan."\n\n".$visual."\n\nMelalui rekaman seismograf pada ".$this->date_localized_before." tercatat:\n".$gempa.$sometimes."\n\nRekomendasi:\n".$rekomendasi."\n\nVONA:\n*DIISI MANUAL*";

            return $temp;
        });

        return $contents;
    }

    protected function checkVar()
    {
        $this->bencanas->each(function ($bencana, $key) {
            if ($bencana->magma_var == null)
                $this->compileVar($bencana->gunungapi->code, $this->date); 
        });

        $this->bencanas = BencanaGeologi::orderBy('urutan')
                            ->with([
                                'gunungapi',
                                'pendahuluan',
                                'magma_var' => function ($query) {
                                    $query->whereVarDataDate($this->date_before)
                                        ->whereVarPerwkt('24 Jam');
                                },
                                'vona'
                            ])
                            ->get();

        return $this;
    }

    protected function compileVar($ga_code)
    {
        $this->vars = MagmaVar::whereGaCode($ga_code)
                    ->whereVarDataDate($this->date_before)
                    ->whereVarPerwkt('6 Jam')
                    ->get();

        if ($this->vars->isNotEmpty()) {
            $this->vars->last()->replicate()
                ->fill($this->mergedVisual())
                ->fill($this->mergedGempa())
                ->save();
        }

        return $this;
    }

    protected function mergedVisual()
    {
        return [
            'var_noticenumber' => $this->noticenumber,
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

    protected function saveStatistic($date = null)
    {
        $stats = StatistikResumeHarian::firstOrCreate([
            'date' => $date ?: now()->format('Y-m-d'),
            'nip' => auth()->user()->nip
        ],[
            'hit' => 0
        ]);

        $stats->increment('hit');

        return $this;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $resumes = ResumeHarian::orderByDesc('tanggal')->paginate(10);
        $pendahuluans = Pendahuluan::has('gunungapi')->with('gunungapi')->get();
        $gadds = Gadd::doesntHave('bencana_geologi')->orderBy('name')->select('code','name')->get();
        $bencanas = BencanaGeologi::orderBy('urutan')->with('pendahuluan')->get();

        $this->saveStatistic();

        return view('gunungapi.resume-harian.index', compact('resumes','pendahuluans','gadds','bencanas'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $this->validate($request, [
            'date' => 'nullable|date_format:Y-m-d'
        ],[
            'date.date_format' => 'Format tanggal harus Y-m-d'
        ]);

        $this->date = $request->has('date') ? 
                Carbon::createFromFormat('Y-m-d', $request->date)->format('Y-m-d'): 
                now()->format('Y-m-d');

        $this->date_before = $request->has('date') ? 
                Carbon::createFromFormat('Y-m-d', $request->date)->subDay()->format('Y-m-d'): 
                now()->subDay()->format('Y-m-d');

        $this->noticenumber = $request->has('date') ? 
                Carbon::createFromFormat('Y-m-d', $request->date)->subDay()->format('Ymd').'2400': 
                now()->subDay()->format('Ymd').'2400';

        $this->date_localized = $request->has('date') ? 
                Carbon::createFromFormat('Y-m-d', $request->date)->formatLocalized('%d %B %Y'): 
                now()->formatLocalized('%d %B %Y');

        $this->date_localized_before = $request->has('date') ? 
                Carbon::createFromFormat('Y-m-d', $request->date)->subDay()->formatLocalized('%d %B %Y'): 
                now()->subDay()->formatLocalized('%d %B %Y');

        $this->bencanas = BencanaGeologi::orderBy('urutan')
                            ->with([
                                'gunungapi',
                                'pendahuluan',
                                'magma_var' => function ($query) {
                                    $query->whereVarDataDate($this->date_before)
                                        ->whereVarPerwkt('24 Jam');
                                },
                                'vona'
                            ])
                            ->get();

        $contents = $this->checkVar()->generateContents()->getContents();
        $resume = str_replace('&deg;C','°C',implode("\n\n", $contents->toArray()));

        ResumeHarian::updateOrCreate([
            'tanggal' => $this->date
        ],[
            'resume' => $resume,
            'truncated' => (strlen($resume) > 20) ? substr($resume, 0, 200) . '...' : $resume,
        ]);

        $this->saveStatistic($this->date);

        return redirect()->route('chambers.resume-harian.index')->with('flash_resume','Resume Harian '.$this->date_localized.' berhasil dibuat!');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        return redirect()->route('chambers.resume-harian.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\ResumeHarian  $resumeHarian
     * @return \Illuminate\Http\Response
     */
    public function show(ResumeHarian $resumeHarian)
    {
        return view('gunungapi.resume-harian.show', compact('resumeHarian'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\ResumeHarian  $resumeHarian
     * @return \Illuminate\Http\Response
     */
    public function edit()
    {
        return redirect()->route('chambers.resume-harian.index');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\ResumeHarian  $resumeHarian
     * @return \Illuminate\Http\Response
     */
    public function update()
    {
        return redirect()->route('chambers.resume-harian.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\ResumeHarian  $resumeHarian
     * @return \Illuminate\Http\Response
     */
    public function destroy()
    {
        return redirect()->route('chambers.resume-harian.index');
    }
}
