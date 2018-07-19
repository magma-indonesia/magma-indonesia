<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Gadd;
use App\PosPga;
use App\MagmaVar;

class MagmaVarController extends Controller
{
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
    public function create()
    {
        $pgas = PosPga::select('code_id','obscode')->orderBy('obscode')->get();
        return view('gunungapi.laporan.create',compact('pgas'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
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
