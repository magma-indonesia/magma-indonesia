<?php

namespace App\Http\Controllers;

use App\Glossary;
use Illuminate\Http\Request;

class GlossaryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return redirect()->route('chambers.edukasi.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('glossary.create');
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
     * @param  \App\Glossary  $glossary
     * @return \Illuminate\Http\Response
     */
    public function show(Glossary $glossary)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Glossary  $glossary
     * @return \Illuminate\Http\Response
     */
    public function edit(Glossary $glossary)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Glossary  $glossary
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Glossary $glossary)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Glossary  $glossary
     * @return \Illuminate\Http\Response
     */
    public function destroy(Glossary $glossary)
    {
        //
    }
}
