<?php

namespace App\Http\Controllers;

use App\Gadd;
use App\BencanaGeologiPendahuluan;
use Illuminate\Http\Request;

class BencanaGeologiPendahuluanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return redirect()->route('chambers.bencana-geologi.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $gadds = Gadd::orderBy('name')->select('code','name')->get();
        return view('bencana-geologi.pendahuluan.create', compact('gadds'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        BencanaGeologiPendahuluan::create([
            'code' => $request->code,
            'pendahuluan' => $request->pendahuluan
        ]);

        return redirect()->route('chambers.bencana-geologi.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\BencanaGeologiPendahuluan  $bencanaGeologiPendahuluan
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        return redirect()->route('chambers.bencana-geologi.index');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\BencanaGeologiPendahuluan  $bencanaGeologiPendahuluan
     * @return \Illuminate\Http\Response
     */
    public function edit(BencanaGeologiPendahuluan $bencanaGeologiPendahuluan)
    {
        $gadds = Gadd::orderBy('name')->select('code','name')->get();
        $pendahuluan = BencanaGeologiPendahuluan::whereId($bencanaGeologiPendahuluan->id)->with('gunungapi')->first();
        return view('bencana-geologi.pendahuluan.edit', compact('gadds','pendahuluan'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\BencanaGeologiPendahuluan  $bencanaGeologiPendahuluan
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, BencanaGeologiPendahuluan $bencanaGeologiPendahuluan)
    {
        $bencanaGeologiPendahuluan->code = $request->code;
        $bencanaGeologiPendahuluan->pendahuluan = $request->pendahuluan;
        $bencanaGeologiPendahuluan->save();

        return redirect()->route('chambers.bencana-geologi.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\BencanaGeologiPendahuluan  $bencanaGeologiPendahuluan
     * @return \Illuminate\Http\Response
     */
    public function destroy(BencanaGeologiPendahuluan $bencanaGeologiPendahuluan)
    {
        //
    }
}
