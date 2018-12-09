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
use App\VarAsap;
use App\VarRekomendasi;
use App\Http\Requests\CreateVar;
use App\Http\Requests\SelectVarRekomendasi;
use App\Http\Requests\DeleteVarRekomendasi;
use App\Http\Requests\CreateVarVisual;
use App\Http\Requests\CreateVarGempa;
use App\Http\Requests\CreateVarKlimatologi;

use App\Traits\JenisGempaVar;

class MagmaVarController extends Controller
{

    use JenisGempaVar;

    /**
     * Properti untuk session var_visual
     *
     * @var array
     */
    protected $varVisual;

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
     * Set session untuk variable VAR
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\MagmaVar $var
     * @return void
     */
    protected function setVarSession($request)
    {
        $pga = PosPga::where('obscode',$request->code)->first();
        $slug = 'laporan gunung api '.$pga->gunungapi->name.' level '.$request->status.' tanggal '.$request->date.' periode '.$request->periode;

        $var = [
            'noticenumber' => $request->noticenumber,
            'slug' => str_slug($slug),
            'code_id' => substr($request->code,0,3),
            'var_data_date' => $request->date,
            'periode' => $request->periode,
            'var_perwkt' => $request->perwkt,
            'obscode_id' => $request->code,
            'status' => $request->status,
            'rekomendasi_id' => null,
            'nip_pelapor' => auth()->user()->nip
        ];

        $request->session()->put('var',$var);
        return $this;
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
        $request->session()->put('var.rekomendasi_id',$this->rekomendasi_id);
        return $this;
    }

    /**
     * Set Variable session Visual untuk memeriksa foto visual temporary 
     *
     * @param \Illuminate\Http\Request $request
     * @return void
     */
    protected function setVarVisual($request)
    {
        $this->requestVarVisual = $request;
        $this->varVisual = $request->session()->has('var_visual') ? 
                                $request->session()->get('var_visual') : 
                                ['foto' => null];

        return $this;
    }

    /**
     * Delete temporary photo sebelum finisihing upload
     *
     * @return void
     */
    protected function deletePhoto()
    {
        $this->varVisual['foto'] ?
                    Storage::disk('temp')->delete('var/'.$this->varVisual['foto']) :
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
        $filename = $this->requestVarVisual->hasfoto == '1' ?
                                'var_temp_'.time().'.'.$this->requestVarVisual->foto->getClientOriginalExtension() :
                                null;

        $this->requestVarVisual->hasfoto == '1' ?
                    $this->requestVarVisual->foto->storeAs('var',$filename ,'temp') :
                    null;
        
        return $filename;
    }

    /**
     * Delete file foto tambahan lainnya
     *
     * @return void
     */
    protected function deletePhotoLainnya()
    {
        if (!empty($this->varVisual['foto_lainnya']))
        {
            foreach ($this->varVisual['foto_lainnya'] as $key => $value) 
            {
                Storage::disk('temp')->delete('var/'.$value);
            }
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
        if ($this->requestVarVisual->has('foto_lainnya'))
        {
            foreach ($this->requestVarVisual->foto_lainnya as $key => $others) {
                $filename_others[] = 'var_temp_lainnya_'.time().'_'.uniqid( ).'.'.$others->getClientOriginalExtension();
                $others->storeAs('var',$filename_others[$key],'temp');
            }
        }

        return $filename_others;
    }

    /**
     * Set session untuk variable Var Visual
     *
     * @param \Illuminate\Http\Request $request
     * @return void
     */
    protected function setVarVisualSession($request)
    {

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
        
        $request->session()->put('var_visual',$visual);

        return $this;
    }

    /**
     * Set session untuk data Kegempaan
     *
     * @param \Illuminate\Http\Request $request
     * @return void
     */
    protected function setVarKegempaanSession($request)
    {
        $request->session()->put('var_gempa',$request->except(['_token']));

        return $this;
    }

    /**
     * Set session untuk Klimatologi
     *
     * @param \Illuminate\Http\Request $request
     * @return void
     */
    protected function setVarKlimatologiSession($request)
    {
        $request->session()->put('var_klimatologi',$request->except(['_token']));

        return $this;
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
        $var = $request->session()->get('var');
        $pgas = PosPga::select('code_id','obscode')->orderBy('obscode')->get();
        return view('gunungapi.laporan.createVar',compact('pgas','var'));
    }

    /**
     * Create laporan MAGMA-VAR 
     * Pilih data rekomendasi
     *
     * @return \Illuminate\Http\Response
     */
    public function selectVarRekomendasi(Request $request)
    {
        if (empty($request->session()->get('var'))) {
            return redirect()->route('chambers.laporan.create.var');
        }

        $rekomendasi = VarRekomendasi::select('id','code_id','rekomendasi')
                            ->where('code_id',session('var')['code_id'])
                            ->where('status',session('var')['status'])
                            ->orderByDesc('created_at')
                            ->get();

        return view('gunungapi.laporan.selectVarRekomendasi',compact('rekomendasi'));
    }

    /**
     * Create laporan MAGMA-VAR
     * Meliputi data dasar Visual laporan
     *
     * @return \Illuminate\Http\Response
     */
    public function createVarVisual(Request $request)
    {
        if (!$request->session()->has('users')) {
            return redirect()->route('chambers.laporan.create.var');
        }

        if (session('var')['rekomendasi_id'] === NULL) {
            return redirect()->route('chambers.laporan.create.var');
        }

        $visual = $request->session()->get('var_visual');
        
        return view('gunungapi.laporan.createVarVisual',compact('visual'));
    }

    /**
     * Create laporan MAGMA-VAR
     * Input data-data klimatologi Gunung Api
     *
     * @return \Illuminate\Http\Response
     */
    public function createVarKlimatologi(Request $request)
    {
        if (empty($request->session()->get('var'))) {
            return redirect()->route('chambers.laporan.create.var');
        }

        if (empty($request->session()->get('var_visual'))) {
            return redirect()->route('chambers.laporan.create.var.visual');
        }

        $klimatologi = $request->session()->get('var_klimatologi');

        return view('gunungapi.laporan.createVarKlimatologi',compact('var_klimatologi'));
    }

    /**
     * Create laporan MAGMA-VAR
     * Input data-data kegempaan Gunung Api
     *
     * @return \Illuminate\Http\Response
     */
    public function createVarGempa(Request $request)
    {
        if (empty($request->session()->get('var'))) {
            return redirect()->route('chambers.laporan.create.var');
        }

        if (empty($request->session()->get('var_visual'))) {
            return redirect()->route('chambers.laporan.create.var.visual');
        }

        if (empty($request->session()->get('var_klimatologi'))) {
            return redirect()->route('chambers.laporan.create.var.klimatologi');
        }

        $jenisgempa = collect($this->jenisgempa())->chunk(10);

        return view('gunungapi.laporan.createVarGempa',compact('jenisgempa'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\CreateVar  $request
     * @return \Illuminate\Http\Response
     */
    public function storeVar(CreateVar $request)
    {
        $this->setVarSession($request);

        return redirect()->route('chambers.laporan.select.var.rekomendasi');
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
     * Store session for Gempa
     *
     * @param \App\Http\Requests\CreateVarGempa $request
     * @return \Illuminate\Http\Response
     */
    public function storeVarGempa(CreateVarGempa $request)
    {
        $this->setVarKegempaanSession($request);

        return $request->session()->all();
    }

    protected function saveVar()
    {
        try {

            $this->hasSaved = true;
            return $this;
        } 
        
        catch (Exception $e) {
            $this->hasSaved = false;
            return $this;
        }
    }

    protected function saveVarVisual()
    {
        try {

            $this->hasSaved = true;
            return $this;
        } 
        
        catch (Exception $e) {
            $this->hasSaved = false;
            return $this;
        }
    }

    protected function saveVarKlimatologi()
    {
        try {

            $this->hasSaved = true;
            return $this;
        } 
        
        catch (Exception $e) {
            $this->hasSaved = false;
            return $this;
        }
    }

    protected function saveVarGempa()
    {
        try {

            $this->hasSaved = true;
            return $this;
        } 
        
        catch (Exception $e) {
            $this->hasSaved = false;
            return $this;
        }
    }

    protected function validateSaveVar()
    {
        return $this->hasSaved ? true : false;
    }

    /**
     * Save to database
     *
     * @return Boolean 
     */
    protected function saveVarSession()
    {
        return $this->saveVar()
                ->saveVarVisual()
                ->saveVarKlimatologi()
                ->saveVarGempa()
                ->validateSaveVar();
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
        $var = MagmaVar::select('status','code_id','var_data_date','periode','var_perwkt','noticenumber')
                ->where('code_id',$code)
                ->where('var_data_date',$request->date)
                ->orderBy('created_at')
                ->get();

        return response()->json($var);
    }
}
