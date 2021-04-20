<?php

namespace App\Http\Controllers\v1;

use App\v1\Kantor;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\v1\Gadd;
use App\v1\PosPga;

class KantorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $kantors = PosPga::orderBy('observatory')
            ->with('users')->has('users')->withCount('users')->get();
        return view('v1.kantor.index', ['kantors' => $kantors]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function indexByPosPengamatan()
    {
        $kantors = PosPga::orderBy('observatory')
            ->whereNotIn('obscode', ['BTK','PAG','PSM','PSG','PVG','BGL'])
            ->with('users')->has('users')->withCount('users')->get();

        return view('v1.kantor.index', ['kantors' => $kantors]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function indexByGunungApi()
    {
        $gadds = Gadd::orderBy('ga_nama_gapi')
            ->select('ga_code','ga_nama_gapi', 'ga_prov_gapi')
            ->with('users')->has('users')->withCount('users', 'pos_pgas')->get();

        return view('v1.kantor.gunung-api', ['gadds' => $gadds]);
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
     * @param  \App\v1\Kantor  $kantor
     * @return \Illuminate\Http\Response
     */
    public function show(Kantor $kantor)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\v1\Kantor  $kantor
     * @return \Illuminate\Http\Response
     */
    public function edit(Kantor $kantor)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\v1\Kantor  $kantor
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Kantor $kantor)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\v1\Kantor  $kantor
     * @return \Illuminate\Http\Response
     */
    public function destroy(Kantor $kantor)
    {
        //
    }
}
