<?php

namespace App\Http\Controllers\FrontPage\v1;

use App\Edukasi;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class EdukasiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('v1.home.edukasi-index', ['edukasis' => Edukasi::with('edukasi_files')->simplePaginate(12)]);
    }

    /**
     * Display the specified resource.
     *
     * @param  String $slug
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $slug)
    {
        return Edukasi::whereSlug($slug)->with('edukasi_files')->firstOrFail();
    }
}
