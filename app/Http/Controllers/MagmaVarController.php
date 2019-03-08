<?php

namespace App\Http\Controllers;

use Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use App\User;
use App\Gadd;
use App\PosPga;
use App\MagmaVar;
use App\VarVisual;
use App\VarKlimatologi;
use App\VarAsap;
use App\VarRekomendasi;
use App\DraftMagmaVar as Draft;
use App\Http\Requests\CreateVar;
use App\Http\Requests\SelectVarRekomendasi;
use App\Http\Requests\DeleteVarRekomendasi;
use App\Http\Requests\CreateVarVisual;
use App\Http\Requests\CreateVarKlimatologi;
use App\Http\Requests\CreateVarGempa;
use App\Http\Requests\CreateMagmaVar;
use Illuminate\Support\Facades\Cache;

use App\Traits\JenisGempaVar;
use App\Traits\VisualAsap;

class MagmaVarController extends Controller
{

    use JenisGempaVar, VisualAsap;

    /**
     * Noticenumber Magma Var
     *
     * @var string
     */
    protected $noticenumber;

    /**
     * Draft MAGMA-VAR
     *
     * @var \App\DraftMagmaVar
     */
    protected $draft;

    /**
     * MAGMA-VAR model
     *
     * @var \App\MagmaVar
     */
    protected $magma_var;

    /**
     * Save id rekomendasi after created
     *
     * @var array
     */
    protected $rekomendasi_id;

    /**
     * Properti untuk request var_visual (bukan session)
     *
     * @param \Illuminate\Http\Request $request
     */
    protected $requestVarVisual;

    /**
     * Initiate for saving status
     *
     * @param Boolean
     */
    protected $hasSaved = false;

    /**
     * Set Variable session Visual untuk memeriksa foto visual temporary 
     *
     * @param \Illuminate\Http\Request $request
     * @return void
     */
    protected function setVarVisual($request)
    {
        $this->requestVarVisual = $request;
        return $this;
    }

    /**
     * Delete temporary photo sebelum finisihing upload
     *
     * @return void
     */
    protected function deletePhoto()
    {
        $this->requestVarVisual->foto || $this->requestVarVisual->hasfoto == 0 ?
                    Storage::disk('temp')->delete('var/'.session('var_visual')['foto']) :
                    false;
        
        return $this;
    }

    /**
     * Updating file foto visual
     *
     * @return String $filename
     */
    protected function updatePhoto()
    {
        if ($this->requestVarVisual->hasfoto == 0)
            return null;

        if ($this->requestVarVisual->has('foto')) {
            $filename = $this->requestVarVisual->hasfoto == '1' ?
                            'var_temp_'.time().'.'.$this->requestVarVisual->foto->getClientOriginalExtension() :
                            session('var_visual')['foto'];

            $this->requestVarVisual->hasfoto == '1' ?
                        $this->requestVarVisual->foto->storeAs('var',$filename ,'temp') :
                        null;
            
            return $filename;
        }

        return session('var_visual')['foto'];

    }

    /**
     * Delete file foto tambahan lainnya
     *
     * @return void
     */
    protected function deletePhotoLainnya()
    {
        if (!empty(session('var_visual')['foto_lainnya']))
        {
            if ($this->requestVarVisual->hasfoto == 0)
            {
                foreach (session('var_visual')['foto_lainnya'] as $key => $value) 
                {
                    Storage::disk('temp')->delete('var/'.$value);
                }
    
                return $this;
            }

            if (!$this->requestVarVisual->has('foto_lainnya') && $this->requestVarVisual->hapus_foto_lainnya == 1)
            {
                foreach (session('var_visual')['foto_lainnya'] as $key => $value) 
                {
                    Storage::disk('temp')->delete('var/'.$value);
                }
    
                return $this;
            }

            if (!$this->requestVarVisual->has('foto_lainnya'))
                return $this;

            if ($this->requestVarVisual->has('foto_lainnya'))
            {
                foreach (session('var_visual')['foto_lainnya'] as $key => $value) 
                {
                    Storage::disk('temp')->delete('var/'.$value);
                }
    
                return $this;
            }

            foreach (session('var_visual')['foto_lainnya'] as $key => $value) 
            {
                Storage::disk('temp')->delete('var/'.$value);
            }

            return $this;
        }

        return $this;
    }

    /**
     * Updating file foto lainnya
     *
     * @return String $filename_others
     */
    protected function updatePhotoLainnya($filename_others = array())
    {
        if ($this->requestVarVisual->hasfoto == 0)
            return $filename_others;

        if (!empty(session('var_visual')['foto_lainnya']) && !$this->requestVarVisual->has('foto_lainnya'))
        {
            if ($this->requestVarVisual->hapus_foto_lainnya == 1)
                return $filename_others;
        
            return session('var_visual')['foto_lainnya'];
        }

        if ($this->requestVarVisual->has('foto_lainnya'))
        {
            foreach ($this->requestVarVisual->foto_lainnya as $key => $others) {
                $filename_others[] = 'var_temp_lainnya_'.time().'_'.uniqid( ).'.'.$others->getClientOriginalExtension();
                $others->storeAs('var',$filename_others[$key],'temp');
            }

            return $filename_others;
        }

        return $filename_others;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Create laporan MAGMA-VAR 
     * Meliputi data dasar laporan
     *
     * @return \Illuminate\Http\Response
     */
    public function createVar(Request $request)
    {
        $var = session('var');
        $pgas = Cache::remember('pos_pga', 120, function() {
            return PosPga::select('code_id','obscode')->orderBy('obscode')->get();
        });
        return view('gunungapi.laporan.createVar',compact('pgas','var'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\CreateVar  $request
     * @return \Illuminate\Http\Response
     */
    public function storeVar(CreateVar $request)
    {
        $draft = $this->setVarSession($request);
        return redirect()->route('chambers.laporan.select.var.rekomendasi');
    }

        /**
     * Set session untuk variable VAR
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\MagmaVar $var
     * @return void
     */
    protected function setVarSession($request)
    {
        try {
            $pga = PosPga::where('obscode',$request->code)->first();
            $slug = 'laporan gunung api '.$pga->gunungapi->name.' level '.$request->status.' tanggal '.$request->date.' periode '.$request->periode;
    
            $var = [
                'noticenumber' => $request->noticenumber,
                'slug' => str_slug($slug),
                'code_id' => substr($request->code,0,3),
                'gunung_api' => $pga->gunungapi,
                'var_data_date' => $request->date,
                'periode' => $request->periode,
                'var_perwkt' => $request->perwkt,
                'obscode_id' => $request->code,
                'status' => $request->status,
                'rekomendasi_id' => null,
                'nip_pelapor' => auth()->user()->nip
            ];
            
            $draft = Draft::updateOrCreate(
                [
                    'noticenumber' => $var['noticenumber'],
                ],
                [
                    'code_id' => $var['code_id'],
                    'nip_pelapor' => $var['nip_pelapor'],
                    'var' => $var,
                ]
            );

            if (session('var')['noticenumber'] != $request->noticenumber)
                $request->session()->forget(['var', 'var_visual','var_klimatologi','var_gempa']);

            session(['var' => $var]);

            return $draft;
        }

        catch (Exception $e) {
            return $e;
        }
    }

    /**
     * Create laporan MAGMA-VAR 
     * Pilih data rekomendasi
     *
     * @return \Illuminate\Http\Response
     */
    public function selectVarRekomendasi(Request $request, $noticenumber = null)
    {
        if (empty(session('var')))
            return redirect()->route('chambers.laporan.create.var');

        $rekomendasi = VarRekomendasi::select('id','code_id','rekomendasi')
                            ->where('code_id',session('var')['code_id'])
                            ->where('status',session('var')['status'])
                            ->orderByDesc('created_at')
                            ->get();

        return view('gunungapi.laporan.selectVarRekomendasi',compact('rekomendasi'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\SelectVarRekomendasi  $request
     * @return \Illuminate\Http\Response
     */
    public function storeVarRekomendasi(SelectVarRekomendasi $request)
    {
        $this->rekomendasi_id = $request->rekomendasi;

        $request->rekomendasi == '9999' ? 
                $this->createRekomendasi($request)->setVarRekomendasiSession($request) :
                $this->setVarRekomendasiSession($request);

        return redirect()->route('chambers.laporan.create.var.visual');
    }

    /**
     * Set session untuk data Kegempaan
     *
     * @param \Illuminate\Http\Request $request
     * @return void
     */
    protected function createRekomendasi($request)
    {
        $rekomendasi = new VarRekomendasi;
        $rekomendasi->code_id = session('var')['code_id'];
        $rekomendasi->status = session('var')['status'];
        $rekomendasi->rekomendasi = $request->rekomendasi_text;
        $rekomendasi->save();

        $this->rekomendasi_id = $rekomendasi->id;
 
        return $this;
    }

    /**
     * Add rekomendasi to VAR Session
     *
     * @param \Illuminate\Http\Request $request
     * @return void
     */
    protected function setVarRekomendasiSession($request)
    {
        try {
            $request->session()->put('var.rekomendasi_id',$this->rekomendasi_id);
            $var = session('var');
            $draft = Draft::updateOrCreate(
                [
                    'noticenumber' => $var['noticenumber'],
                ],
                [
                    'code_id' => $var['code_id'],
                    'nip_pelapor' => $var['nip_pelapor'],
                    'var' => $var,
                ]
            );

            return $draft;
        }

        catch (Exception $e) {
            return $e;
        }

    }

    /**
     * Create laporan MAGMA-VAR
     * Meliputi data dasar Visual laporan
     *
     * @return \Illuminate\Http\Response
     */
    public function createVarVisual(Request $request)
    {
        if (!$request->session()->has('var'))
            return redirect()->route('chambers.laporan.create.var');

        if (session('var')['rekomendasi_id'] === NULL)
            return redirect()->route('chambers.laporan.create.var');

        $visual = session('var_visual');
        
        return view('gunungapi.laporan.createVarVisual',compact('visual'));
    }

    /**
     * Store session for Visual Information
     *
     * @param \App\Http\Requests\CreateVarVisual $request
     * @return \Illuminate\Http\Response
     */
    public function storeVarVisual(CreateVarVisual $request)
    {
        $this->setVarVisualSession($request);
        return redirect()->route('chambers.laporan.create.var.klimatologi');
    }

    /**
     * Set session untuk variable Var Visual
     *
     * @param \Illuminate\Http\Request $request
     * @return void
     */
    protected function setVarVisualSession($request)
    {

        try {
            $visual = [
                'visibility' => $request->visibility ? $request->visibility : array(),
                'visual_asap' => $request->visual_asap,
                'hasfoto' => $request->hasfoto,
                'foto' => $this->setVarVisual($request)->deletePhoto()->updatePhoto(),
                'foto_lainnya' => $this->setVarVisual($request)->deletePhotoLainnya()->updatePhotoLainnya(),
                'tasap_min' => $request->tasap_min,
                'tasap_max' => $request->tasap_max,
                'wasap' => $request->wasap,
                'intasap' => $request->intasap,
                'tekasap' => $request->tekasap,
                'visual_kawah' => $request->visual_kawah,
            ];

            $draft = Draft::updateOrCreate(
                [
                    'noticenumber' => session('var')['noticenumber'],
                ],
                [
                    'var_visual' => $visual,
                ]
            );

            session(['var_visual' => $visual]);

            return $draft;
        }

        catch (Exception $e) {
            return $e;
        }
    }

    /**
     * Create laporan MAGMA-VAR
     * Input data-data klimatologi Gunung Api
     *
     * @return \Illuminate\Http\Response
     */
    public function createVarKlimatologi(Request $request)
    {
        if (empty(session('var')))
            return redirect()->route('chambers.laporan.create.var');

        if (empty(session('var_visual')))
            return redirect()->route('chambers.laporan.create.var.visual');

        $klimatologi = session('var_klimatologi');

        return view('gunungapi.laporan.createVarKlimatologi',compact('klimatologi'));
    }

    /**
     * Set session untuk Klimatologi
     *
     * @param \Illuminate\Http\Request $request
     * @return void
     */
    protected function setVarKlimatologiSession($request)
    {
        try {
            $klimatologi = $request->except(['_token']);
            $draft = Draft::updateOrCreate(
                [
                    'noticenumber' => session('var')['noticenumber'],
                ],
                [
                    'var_klimatologi' => $klimatologi,
                ]
            );

            session(['var_klimatologi' => $klimatologi]);

            return $draft;
        }

        catch (Exception $e) {
            return $e;
        }
        
        return $this;
    }

    /**
     * Store session for Klimatologi
     *
     * @param \App\Http\Requests\CreateVarKlimatologi $request
     * @return \Illuminate\Http\Response
     */
    public function storeVarKlimatologi(CreateVarKlimatologi $request)
    {
        $this->setVarKlimatologiSession($request);
        return redirect()->route('chambers.laporan.create.var.gempa');
    }

    /**
     * Create laporan MAGMA-VAR
     * Input data-data kegempaan Gunung Api
     *
     * @return \Illuminate\Http\Response
     */
    public function createVarGempa(Request $request)
    {
        if (empty(session('var')))
            return redirect()->route('chambers.laporan.create.var');

        if (empty(session('var_visual')))
            return redirect()->route('chambers.laporan.create.var.visual');

        if (empty(session('var_klimatologi')))
            return redirect()->route('chambers.laporan.create.var.klimatologi');

        $jenisgempa = collect($this->jenisgempa())->chunk(10);
        $gempa = session(
            'var_gempa',
            [
                'has_gempa' => '0'
            ]
        );

        return view('gunungapi.laporan.createVarGempa',compact('jenisgempa','gempa'));
    }

    /**
     * Store session for Gempa
     *
     * @param \App\Http\Requests\CreateVarGempa $request
     * @return \Illuminate\Http\Response
     */
    public function storeVarGempa(CreateVarGempa $request)
    {
        $this->setVarKegempaanSession($request);
        return redirect()->route('chambers.laporan.preview.magma.var');
    }

    /**
     * Set session untuk data Kegempaan
     *
     * @param \Illuminate\Http\Request $request
     * @return void
     */
    protected function setVarKegempaanSession($request)
    {
        try {
            $kegempaan = $request->except(['_token']);
            $draft = Draft::updateOrCreate(
                [
                    'noticenumber' => session('var')['noticenumber'],
                ],
                [
                    'var_gempa' => $kegempaan,
                ]
            );

            session(['var_gempa' => $kegempaan]);

            return $draft;
        }

        catch (Exception $e) {
            return $e;
        }
        
        return $this;
    }

    /**
     * Create laporan MAGMA-VAR
     * Input data-data kegempaan Gunung Api
     *
     * @return \Illuminate\Http\Response
     */
    public function previewMagmaVar(Request $request)
    {
        // $request->session()->forget('var_gempa');
        if (empty(session('var')))
            return redirect()->route('chambers.laporan.create.var');

        if (empty(session('var_visual')))
            return redirect()->route('chambers.laporan.create.var.visual');

        if (empty(session('var_klimatologi')))
            return redirect()->route('chambers.laporan.create.var.klimatologi');

        if (empty(session('var_gempa')))
            return redirect()->route('chambers.laporan.create.var.gempa');
    
        $var = (object) session('var');
        $var_visual = (object) session('var_visual');
        $var_klimatologi = (object) session('var_klimatologi');
        $var_gempa = (object) session('var_gempa');

        $asap = (object) [
            'wasap' => isset($var_visual->wasap) ? $var_visual->wasap : [],
            'intasap' => isset($var_visual->intasap) ? $var_visual->intasap : [], 
            'tasap_min' => $var_visual->tasap_min,
            'tasap_max' => $var_visual->tasap_max,
        ];

        $visual = $this->visibility($var_visual->visibility)
                ->asap($var_visual->visual_asap, $asap)
                ->cuaca($var_klimatologi->cuaca)
                ->angin($var_klimatologi->kecepatan_angin,$var_klimatologi->arah_angin)
                ->getVisual();
 
        return view('gunungapi.laporan.previewMagmaVar',compact('var','var_visual','var_klimatologi','var_gempa','visual'));
    }

    /**
     * Store all session - Final
     *
     * @param \App\Http\Requests\CreateMagmaVar $request
     * @return \Illuminate\Http\Response
     */
    public function storePreviewMagmaVar(CreateMagmaVar $request)
    {
        return $this->setNoticeNumber()->getDraft()->saveMagmaVar()->getSaveStatus();
    }

    protected function setNoticeNumber()
    {
        $this->noticenumber = session('var')['noticenumber'];
        return $this;
    }

    protected function getDraft()
    {
        $this->draft = Draft::findOrFail($this->noticenumber);
        return $this;
    }

    protected function saveMagmaVar()
    {
        $this->saveVar()
            ->saveVisual()
            ->saveKlimatologi()
            ->saveKegempaan()
            ->saveRekomendasi()
            ->setSaveStatus();

        return $this;
    }

    protected function saveVar()
    {
        try {
            $draft = $this->draft;
            $var = $draft->var;
            $this->magma_var = MagmaVar::firstOrCreate(
                [
                    'noticenumber' => $draft->noticenumber
                ],
                [
                    'slug' => $var['slug'],
                    'code_id' => $var['code_id'],
                    'var_data_date' => $var['var_data_date'],
                    'periode' => $var['periode'],
                    'var_perwkt' => $var['var_perwkt'],
                    'obscode_id' => $var['obscode_id'],
                    'status' => $var['status'],
                    'rekomendasi_id' => $var['rekomendasi_id'],
                    'nip_pelapor' => $var['nip_pelapor'],
                ]
            );

            $draft->var_saved = 1;
            $draft->save();
            return $this;
        }

        catch (Exception $e)
        {
            return back()->withError('Gagal menyimpan data MAGMA-VAR '.$var->noticenumber);
        }

        return $this;
    }

    protected function saveVisual()
    {
        try {
            $draft = $this->draft;
            $var_visual = $draft->var_visual;

            $var_visual = new VarVisual(
                [
                    'visibility' => $var_visual['visibility'],
                    'visual_asap' => $var_visual['visual_asap'],
                    'visual_kawah' => $var_visual['visual_kawah'],
                    'filename_0' => $var_visual['foto'],
                    'filename_1' => isset($var_visual['foto_lainnya'][0]) ? $var_visual['foto_lainnya'][0] : null,
                    'filename_2' => isset($var_visual['foto_lainnya'][1]) ? $var_visual['foto_lainnya'][1] : null,
                    'filename_3' => isset($var_visual['foto_lainnya'][2]) ? $var_visual['foto_lainnya'][2] : null,
                    'file_old' => null
                ]
            );

            $var = $this->magma_var;

            $var->visual()->save($var_visual);
        }

        catch (Exception $e)
        {
            return back()->withError('Gagal menyimpan data MAGMA-VAR '.$var->noticenumber);
        }
    }
    
    protected function saveKlimatologi()
    {
        try {
            $draft = $this->draft;
            $var_klimatologi = $draft->var_klimatologi;

            $var_klimatologi = new VarKlimatologi(
                [
                    'cuaca' => $var_klimatologi['cuaca'],
                    'curah_hujan' => $var_klimatologi['curah_hujan'],
                    'kecangin' => $var_klimatologi['kecangin'],
                    'arahangin' => $var_klimatologi['arahangin'],
                    'suhumin' => $var_klimatologi['suhumin'],
                    'suhumax' => $var_klimatologi['suhumax'],
                    'lembabmin' => $var_klimatologi['lembabmin'],
                    'lembabmax' => $var_klimatologi['lembabmax'],
                    'tekmin' => $var_klimatologi['tekmin'],
                    'tekmax' => $var_klimatologi['tekmax'],
                ]
            );

            $this->magma_var->klimatologi()->save($var_klimatologi);
        }

        catch (Exception $e)
        {
            return back()->withError('Gagal menyimpan data MAGMA-VAR '.$var->noticenumber);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroyVarRekomendasi(DeleteVarRekomendasi $request, $id)
    {
        $rekomendasi = VarRekomendasi::findOrFail($id);
        return $rekomendasi->delete() ? 
                    [ 'success' => 1, 'message' => 'Rekomendasi berhasil dihapus'] : 
                    [ 'success' => 0, 'message' => 'Rekomendasi gagal dihapus'];
    }

    /**
     * Check existing VAR
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function exists(Request $request)
    {
        $code = PosPga::where('obscode',$request->code)->firstOrFail()->code_id;
        $var = MagmaVar::select(
                'status','code_id','var_data_date',
                'periode','var_perwkt','noticenumber')
                ->where('code_id',$code)
                ->where('var_data_date',$request->date)
                ->orderBy('created_at')
                ->get();

        return response()->json($var);
    }
}
