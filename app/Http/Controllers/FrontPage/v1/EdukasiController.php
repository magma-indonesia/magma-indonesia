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
        return view('v1.home.edukasi-index', ['edukasis' => Edukasi::withCount('edukasi_files')->whereIsPublished(1)->simplePaginate(12)]);
    }

    /**
     * Display the specified resource.
     *
     * @param  String $slug
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, String $slug)
    {
        return view('v1.home.edukasi-show',['edukasi' => Edukasi::with('edukasi_files')->whereIsPublished(1)->whereSlug($slug)->firstOrFail()]);
    }
}
