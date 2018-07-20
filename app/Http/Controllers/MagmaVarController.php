<?php

namespace App\Http\Controllers;

use Validator;
use Illuminate\Http\Request;
use App\User;
use App\Gadd;
use App\PosPga;
use App\MagmaVar;

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

    public function createStep2()
    {
        return 'create2';
    }

    public function createStep3()
    {
        return 'create3';
    }

    protected function setObscode($obscode)
    {
        $this->obscode = $obscode;
        return $this;
    }

    protected function setDataDate($date)
    {
        $this->data_date = str_replace('-','',$date);
        return $this;
    }

    protected function setPeriode($periode)
    {
        $this->periode = $periode != '00:00-24:00' ? substr($periode,0,2).'00' : '2400';
        $this->perwkt = $periode != '00:00-24:00' ? '6' : '24';
        return $this;
    }

    protected function setNoticenumber($request)
    {
        $this->setObscode($request->code)->setDataDate($request->date)->setPeriode($request->periode);
        $this->noticenumber = $this->obscode.$this->data_date.$this->periode;
        return $this;
    }

    protected function getNoticenumber()
    {
        return $this->noticenumber;
    }

    protected function getPerwkt()
    {
        return $this->perwkt;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeStep1(Request $request)
    {
        $this->setNoticenumber($request);
        $request->merge(['noticenumber' => $this->getNoticenumber()]);
        $request->merge(['perwkt' => $this->getPerwkt()]);

        $messages = [
            'required' => 'Semua form harus diisi.',
            'size' => 'Pos Gunung Api tidak valid.',
            'status.in' => 'Status Gunung Api tidak valid.',
            'date.date_format' => 'Format tanggal tidak valid (Y-m-d).',
            'date.before_or_equal' => 'Tanggal tidak boleh lebih dari hari ini.',
            'periode.in' => 'Periode waktu tidak valid.', 
            'noticenumber.unique' => 'Laporan sudah pernah dibuat.'
        ];
        
        $validator = Validator::make($request->all(),[
            'code' => 'required|size:4',
            'status' => 'required|in:1,2,3,4',
            'date' => 'required|date_format:Y-m-d|before_or_equal:today',
            'periode' => 'required|in:00:00-24:00,00:00-06:00,06:00-12:00,12:00-24:00',
            'noticenumber' => 'required|unique:magma_vars,noticenumber'
        ],$messages)->validate();

        $pga = PosPga::where('obscode',$request->code)->first();
        $gunungapi = $pga->gunungapi->name;
        $slug = 'laporan gunung api '.$gunungapi.' tanggal '.$request->date.' periode '.$request->periode;

        if (empty($request->session()->get('var'))) {
            $var = new MagmaVar();
            $var->fill([
                'noticenumber' => $request->noticenumber,
                'slug' => str_slug($slug),
                'code_id' => substr($request->code,0,3),
                'var_data_date' => $request->date,
                'periode' => $request->periode,
                'var_perwkt' => $request->perwkt,
                'obscode_id' => $request->code,
                'status' => $request->status
            ]);
        } else {
            $var = $request->session()->get('var');
            $var->fill([
                'noticenumber' => $request->noticenumber,
                'slug' => str_slug($slug),
                'code_id' => substr($request->code,0,3),
                'var_data_date' => $request->date,
                'periode' => $request->periode,
                'var_perwkt' => $request->perwkt,
                'obscode_id' => $request->code,
                'status' => $request->status
            ]);
        }

        $request->session()->put('var',$var);

        return redirect()->route('chambers.laporan.create.2');
    }

    public function storeStep2(Request $request)
    {
        return $request;
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
