<?php

namespace App\Http\Controllers\FrontPage\v1;

use App\Glossary;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class GlossaryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('v1.home.glossary-index', ['glossaries' => Glossary::with('glossary_files')->whereIsPublished(1)->orderBy('judul')->get()]);
    }

    /**
     * Display the specified resource.
     *
     * @param  String $slug
     * @return \Illuminate\Http\Response
     */
    public function show(String $slug)
    {
        $glossary = Glossary::whereSlug($slug)->firstOrFail();
        return view('v1.home.glossary-show', ['glossary' => $glossary]);
    }
}
