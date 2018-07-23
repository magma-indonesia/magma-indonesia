<?php

namespace App\Http\Controllers;

use Validator;
use Illuminate\Http\Request;
use App\User;
use App\Gadd;
use App\PosPga;
use App\MagmaVar;
use App\VarVisual;
use App\Http\Requests\CreateVarStep1;
use App\Http\Requests\CreateVarStep2;

class MagmaVarController extends Controller
{

    protected $obscode, $periode, $perwkt, $data_date, $noticenumber;

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
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function createStep1(Request $request)
    {
        $var = $request->session()->get('var');
        $pgas = PosPga::select('code_id','obscode')->orderBy('obscode')->get();
        return view('gunungapi.laporan.create',compact('pgas','var'));
    }

    public function createStep2(Request $request)
    {
        if (empty($request->session()->get('var'))) {
            return redirect()->route('chambers.laporan.create.1');
        }
        
        return view('gunungapi.laporan.create2');
    }

    public function createStep3(Request $request)
    {
        // $request->session()->forget('var');
        return $request->session()->get('var');
        return 'create3';
    }

    /**
     * Set session untuk variable VAR
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\MagmaVar $var
     * @return void
     */
    protected function setVarSession($request, $var)
    {
        $pga = PosPga::where('obscode',$request->code)->first();
        $gunungapi = $pga->gunungapi->name;
        $slug = 'laporan gunung api '.$gunungapi.' tanggal '.$request->date.' periode '.$request->periode;

        $var->fill([
            'noticenumber' => $request->noticenumber,
            'slug' => str_slug($slug),
            'code_id' => substr($request->code,0,3),
            'var_data_date' => $request->date,
            'periode' => $request->periode,
            'var_perwkt' => $request->perwkt,
            'obscode_id' => $request->code,
            'status' => $request->status,
            'nip_pelapor' => auth()->user()->nip
        ]);

        $request->session()->put('var',$var);
        return $this;
    }

    protected function setVarVisualSession($request, $varVisual)
    {
        $varVisual->fill([
            'noticenumber_id' => $request->session()->get('var')->noticenumber,
            'visibility' => $request->visibility,
            'visual_asap' => $request->visual_asap,
            'visual_kawah' => $request->visual_kawah
        ]);

        $request->session()->put('var_visual',$varVisual);
        return $this;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\CreateVarStep1  $request
     * @return \Illuminate\Http\Response
     */
    public function storeStep1(CreateVarStep1 $request)
    {
        empty($request->session()->get('var')) 
            ? $this->setVarSession($request, new MagmaVar()) 
            : $this->setVarSession($request, $request->session()->get('var'));

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
        empty($request->session()->get('var_visual')) 
            ? $this->setVarVisualSession($request, new VarVisual()) 
            : $this->setVarVisualSession($request, $request->session()->get('var_visual'));
            
        return $request->session()->get('var_visual');
    }

    public function storeStep3(Request $request)
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
