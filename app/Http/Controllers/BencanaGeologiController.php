<?php

namespace App\Http\Controllers;

use App\Gadd;
use App\BencanaGeologi;
use App\BencanaGeologiPendahuluan as Pendahuluan;
use Illuminate\Http\Request;

class BencanaGeologiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $gadds = Gadd::orderBy('name')->select('code','name')->get();
        $bencanas = BencanaGeologi::orderBy('urutan')->with('pendahuluan')->get();
        $pendahuluans = Pendahuluan::all();
        return view('bencana-geologi.index', compact('gadds','bencanas','pendahuluans'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $this->validate($request, [
            'code' => 'required|unique:bencana_geologis,code'
        ],[
            'code.unique' => 'Gunung Api telah aktif'
        ]);

        $gadd = Gadd::whereCode($request->code)->firstOrFail();
        $pendahuluans = Pendahuluan::whereCode($request->code)->get();
        
        if ($pendahuluans->isEmpty())
            return redirect()->route('chambers.bencana-geologi-pendahuluan.create')->with('flash_message','Anda diarahkan ke halaman ini karena data <b>Pendahuluan untuk gunung api '.$gadd->name.' belum ada</b>');

        return view('bencana-geologi.create', compact('gadd','pendahuluans'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'code' => 'required|unique:bencana_geologis,code'
        ],[
            'code.unique' => 'Gunung Api telah aktif'
        ]);

        $bencana = new BencanaGeologi;
        $bencana->code = $request->code; 
        $bencana->urutan = $request->urutan; 
        $bencana->bencana_geologi_pendahuluan_id = $request->pendahuluan_id; 
        $bencana->active = $request->active;
        $bencana->save();
        
        return redirect()->route('chambers.bencana-geologi.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\BencanaGeologi  $bencanaGeologi
     * @return \Illuminate\Http\Response
     */
    public function show(BencanaGeologi $bencanaGeologi)
    {

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\BencanaGeologi  $bencanaGeologi
     * @return \Illuminate\Http\Response
     */
    public function edit(BencanaGeologi $bencanaGeologi)
    {
        $gadd = Gadd::whereCode($bencanaGeologi->code)->firstOrFail();
        $pendahuluans = Pendahuluan::whereCode($bencanaGeologi->code)->get();

        return view('bencana-geologi.edit', compact('gadd','pendahuluans','bencanaGeologi'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\BencanaGeologi  $bencanaGeologi
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, BencanaGeologi $bencanaGeologi)
    {
        $bencanaGeologi->code = $request->code;
        $bencanaGeologi->urutan = $request->urutan; 
        $bencanaGeologi->bencana_geologi_pendahuluan_id = $request->pendahuluan_id; 
        $bencanaGeologi->active = $request->active;
        $bencanaGeologi->save();

        return redirect()->route('chambers.bencana-geologi.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\BencanaGeologi  $bencanaGeologi
     * @return \Illuminate\Http\Response
     */
    public function destroy(BencanaGeologi $bencanaGeologi)
    {
        //
    }
}
