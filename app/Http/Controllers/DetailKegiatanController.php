<?php

namespace App\Http\Controllers;

use App\MGA\DetailKegiatan;
use App\MGA\Kegiatan;
use App\Gadd;
use App\User;
use Illuminate\Http\Request;

class DetailKegiatanController extends Controller
{
    /**
     * Adding middleware for protecttion
     * 
     * @return boolean
     */
    public function __construct()
    {
        $this->middleware('role:Super Admin,Kortim MGA');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return redirect()->route('chambers.administratif.mga.jenis-kegiatan.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $kegiatan = Kegiatan::findOrFail($request->id);
        $gadds = Gadd::select('code','name')->orderBy('name')->get();
        $users = User::select('name','nip')->orderBy('name')->get();
        return view('mga.detail-kegiatan.create', compact('kegiatan','gadds','users'));
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
     * @param  \App\MGA\DetailKegiatan  $detailKegiatan
     * @return \Illuminate\Http\Response
     */
    public function show(DetailKegiatan $detailKegiatan)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\MGA\DetailKegiatan  $detailKegiatan
     * @return \Illuminate\Http\Response
     */
    public function edit(DetailKegiatan $detailKegiatan)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\MGA\DetailKegiatan  $detailKegiatan
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, DetailKegiatan $detailKegiatan)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\MGA\DetailKegiatan  $detailKegiatan
     * @return \Illuminate\Http\Response
     */
    public function destroy(DetailKegiatan $detailKegiatan)
    {
        //
    }
}
