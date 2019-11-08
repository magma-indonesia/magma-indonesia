<?php

namespace App\Http\Controllers\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use App\v1\Gadd;
use App\v1\History;

class GaddController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $gadds = Gadd::orderBy('ga_nama_gapi')->get();

        return view('v1.gunungapi.gadd.index',compact('gadds'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $gadd = Gadd::with('history')->where('ga_code',$id)->firstOrFail();
        return $gadd;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $gadd = Gadd::with('history')->where('ga_code',$id)->firstOrFail();

        return view('v1.gunungapi.gadd.edit', compact('gadd'));
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

        $gadd = $request->validate([
            'code' => 'required|max:3',
            'name' => 'required|max:255',
            'intro' => 'required',
            'ven' => 'boolean',
            'vona' => 'boolean',
            'tipe' => 'required|in:A,B,C',
            'ketinggian' => 'required|numeric|between:0,10000',
            'latitude' => 'required|numeric|between:-10,6',
            'longitude' => 'required|numeric|between:95,130',
            'provinsi' => 'required|max:255',
            'kota' => 'required|max:255',
        ]);

        $gadd = Gadd::with('history')->where('ga_code',$id)->firstOrFail();
        $gadd->ga_code = $request->code;
        $gadd->ga_nama_gapi = $request->name;
        $gadd->ga_elev_gapi = $request->ketinggian;
        $gadd->ga_lat_gapi = $request->latitude;
        $gadd->ga_lon_gapi = $request->longitude;
        $gadd->ga_tipe_gapi = $request->tipe;
        $gadd->ga_prov_gapi = $request->provinsi;
        $gadd->ga_kab_gapi = $request->kota;
        $gadd->save();

        $history = $gadd->history()->update([
                'ga_code' => $gadd->ga_code,
                'intro' => $request->intro,
                'ven_included' => $request->has('ven') ? $request->ven : 0,
                'vona_included' => $request->has('ven') ? $request->vona : 0,
                'updated_at' => now()->format('Y-m-d H:i:s')
            ]
        );

        $gadds = Gadd::orderBy('ga_nama_gapi')->get();
        
        Cache::put('v1/gadds', $gadds, 360);

        return redirect()->route('chambers.v1.gunungapi.data-dasar.index')
                ->with('flash_message', 'Update '.$gadd->ga_nama_gapi.' berhasil ditambahkan.');

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
}
