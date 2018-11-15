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
use App\Http\Requests\CreateVarStep1;
use App\Http\Requests\CreateVarStep2;
use App\Http\Requests\CreateVarStep3;

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
     * properti untuk request var_visual (bukan session)
     *
     * @param \Illuminate\Http\Request $request
     */
    protected $requestVarVisual;

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
            'nip_pelapor' => auth()->user()->nip
        ];

        $request->session()->put('var',$var);
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
     * Set session untuk data kegempaan
     *
     * @param \Illuminate\Http\Request $request
     * @return void
     */
    protected function setVarKegempaan($request)
    {

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
     * Create laporab MAGMA-VAR langkah 1
     * Meliputi data dasar laporan
     *
     * @return \Illuminate\Http\Response
     */
    public function createStep1(Request $request)
    {
        $var = $request->session()->get('var');
        $pgas = PosPga::select('code_id','obscode')->orderBy('obscode')->get();
        return view('gunungapi.laporan.create',compact('pgas','var'));
    }

    /**
     * Create laporab MAGMA-VAR langkah 2
     * Meliputi data dasar Visual laporan
     *
     * @return \Illuminate\Http\Response
     */
    public function createStep2(Request $request)
    {
        if (empty($request->session()->get('var'))) {
            return redirect()->route('chambers.laporan.create.1');
        }

        $visual = $request->session()->get('var_visual');
        
        return view('gunungapi.laporan.create2',compact('visual'));
    }

    public function createStep3(Request $request)
    {
        $jenisgempa = collect($this->jenisgempa())->chunk(10);
        return view('gunungapi.laporan.create3',compact('jenisgempa'));
        return 'create3';
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\CreateVarStep1  $request
     * @return \Illuminate\Http\Response
     */
    public function storeStep1(CreateVarStep1 $request)
    {
        $this->setVarSession($request);

        return redirect()->route('chambers.laporan.create.2');
    }

    /**
     * Undocumented function
     *
     * @param \App\Http\Requests\CreateVarStep2 $request
     * @return \Illuminate\Http\Response
     */
    public function storeStep2(CreateVarStep2 $request)
    {
        $this->setVarVisualSession($request);

        return redirect()->route('chambers.laporan.create.3');
    }

    public function storeStep3(CreateVarStep3 $request)
    {
        return $request;
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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

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
