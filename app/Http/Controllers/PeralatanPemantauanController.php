<?php

namespace App\Http\Controllers;

use App\PeralatanPemantauan;
use Illuminate\Http\Request;

class PeralatanPemantauanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $gunungApi = PeralatanPemantauan::where('bidang', 'ga')->count();
        $peralatanPemantauan = PeralatanPemantauan::paginate(30);

        return view('peralatan.pemantauan.index', [
            'jumlah' => PeralatanPemantauan::count(),
            'gunung_api' => $gunungApi,
            'peralatanPemantauan' => $peralatanPemantauan,
        ]);
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
     * @param  \App\PeralatanPemantauan  $peralatanPemantauan
     * @return \Illuminate\Http\Response
     */
    public function show(PeralatanPemantauan $peralatanPemantauan)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\PeralatanPemantauan  $peralatanPemantauan
     * @return \Illuminate\Http\Response
     */
    public function edit(PeralatanPemantauan $peralatanPemantauan)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\PeralatanPemantauan  $peralatanPemantauan
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, PeralatanPemantauan $peralatanPemantauan)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\PeralatanPemantauan  $peralatanPemantauan
     * @return \Illuminate\Http\Response
     */
    public function destroy(PeralatanPemantauan $peralatanPemantauan)
    {
        //
    }
}
