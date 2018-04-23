<?php

namespace App\Http\Controllers;

use App\PosPga;
use Illuminate\Http\Request;

use App\Http\Resources\PosCollection;
use App\Http\Resources\PosResource;

class PosPgaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $pgas = new PosCollection(PosPga::all());
        // return $pgas;
        return view('gunungapi.pos.index',compact('pgas'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        
        $pgas = PosPga::where('code_id',$request->id)->get();
        return view('gunungapi.pos.create',compact('pgas'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $pga = PosPga::select('obscode')->where('code_id',$request->code)->orderBy('obscode','desc')->firstOrFail();

        $jumlahpos = (int) filter_var($pga, FILTER_SANITIZE_NUMBER_INT)+1;

        $this->validate($request, [
            'code' => 'required|string|max:3',
            'name' => 'required|string|max:255',
            'alamat' => 'nullable|string|max:255',
            'ketinggian' => 'nullable|numeric',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'keterangan' => 'nullable'
        ]);

        $pga = new PosPga();
        $pga->code_id = $request->code;
        $pga->obscode = $request->code.$jumlahpos;
        $pga->observatory = $request->name;
        $pga->address = $request->alamat;
        $pga->elevation = $request->ketinggian;
        $pga->latitude = $request->latitude;
        $pga->longitude = $request->longitude;
        $pga->keterangan = $request->keterangan; 

        if ($pga->save())
        {
            return redirect()->route('pos.index')->with('flash_message',$request->name.' berhasil ditambahkan.');
        }

        return redirect()->route('pos.index')->with('flash_message','Pos pengamatan gagal ditambahkan.');    

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\PosPga  $posPga
     * @return \Illuminate\Http\Response
     */
    public function show(PosPga $posPga)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\PosPga  $posPga
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $pga = PosPga::findOrFail($id);
        return view('gunungapi.pos.edit',compact('pga'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\PosPga  $posPga
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $pga = PosPga::findOrFail($id);

        $this->validate($request, [
            'code' => 'required|string|max:3',
            'name' => 'required|string|max:255',
            'alamat' => 'nullable|string|max:255',
            'ketinggian' => 'nullable|numeric',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'keterangan' => 'nullable'
        ]);

        $pga->code_id = $request->code;
        $pga->observatory = $request->name;
        $pga->address = $request->alamat;
        $pga->elevation = $request->ketinggian;
        $pga->latitude = $request->latitude;
        $pga->longitude = $request->longitude;
        $pga->keterangan = $request->keterangan;

        if ($pga->save())
        {
            return redirect()->route('pos.index')->with('flash_message',$request->name.' berhasil dirubah.');
        }

        return redirect()->route('pos.index')->with('flash_message','Pos pengamatan gagal dirubah.');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\PosPga  $posPga
     * @return \Illuminate\Http\Response
     */
    public function destroy(PosPga $posPga)
    {
        //
    }
}
