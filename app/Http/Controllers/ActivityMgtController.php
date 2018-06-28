<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\MagmaSigertan;
use App\v1\MagmaSigertan as OldSigertan;

class ActivityMgtController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $sigertans = MagmaSigertan::orderBy('updated_at','desc')
            ->with('ketua','crs','status','kerusakan','kondisi')
            ->orderBy('updated_at','desc')
            ->paginate(30,['*'],'qls_page');

        // return $sigertans;

        return view('gerakantanah.index',compact('sigertans'));
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
}
