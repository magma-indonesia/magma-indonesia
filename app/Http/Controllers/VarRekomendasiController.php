<?php

namespace App\Http\Controllers;

use App\VarRekomendasi;
use App\Gadd;
use Illuminate\Http\Request;

class VarRekomendasiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $gadds = Gadd::select('code','name')
                    ->with('rekomendasi')
                    ->orderBy('name')
                    ->get();
        return view('gunungapi.rekomendasi.index', compact('gadds'));
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
     * @param  \App\VarRekomendasi  $varRekomendasi
     * @return \Illuminate\Http\Response
     */
    public function show(VarRekomendasi $varRekomendasi)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\VarRekomendasi  $varRekomendasi
     * @return \Illuminate\Http\Response
     */
    public function edit(VarRekomendasi $varRekomendasi)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\VarRekomendasi  $varRekomendasi
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, VarRekomendasi $varRekomendasi)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\VarRekomendasi  $varRekomendasi
     * @return \Illuminate\Http\Response
     */
    public function destroy(VarRekomendasi $varRekomendasi)
    {
        //
    }

    public function partial($code, $status)
    {
        $rekomendasis = VarRekomendasi::select('id', 'code_id', 'rekomendasi')
                            ->where('code_id', $code)
                            ->where('status', $status)
                            ->orderByDesc('created_at')
                            ->get();

        return view('gunungapi.letusan.partial-rekomendasi', compact('rekomendasis'));
    }
}
