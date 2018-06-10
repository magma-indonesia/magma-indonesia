<?php

namespace App\Http\Controllers;

use App\RoqTanggapan;
use Illuminate\Http\Request;

class RoqTanggapanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return $roqs = RoqTanggapan::orderBy('id','desc')
            ->paginate(30,['*'],'resp_page');

        return view('gempabumi.tanggapan.index',compact('roqs'));
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
     * @param  \App\RoqTanggapan  $roqTanggapan
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return RoqTanggapan::findOrFail($id);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\RoqTanggapan  $roqTanggapan
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        return RoqTanggapan::findOrFail($id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\RoqTanggapan  $roqTanggapan
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, RoqTanggapan $roqTanggapan)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\RoqTanggapan  $roqTanggapan
     * @return \Illuminate\Http\Response
     */
    public function destroy(RoqTanggapan $roqTanggapan)
    {
        //
    }
}
