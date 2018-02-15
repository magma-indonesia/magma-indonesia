<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Gadd;
use App\History;

class VolcanoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $gadds = Gadd::orderBy('name')->paginate(12);
        return view('volcanoes.index',compact('gadds'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $gadds = Gadd::all();
        
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
        $gadd = new Gadd();
        $gadd->history->body = $request->body;
        $gadd->save();
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
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        // return md5('198707032009121002');

        $gadd = Gadd::findOrFail($id);

        return view('volcanoes.edit', compact('gadd'));
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
        $gadd = Gadd::findOrFail($id);

        $success = $gadd->history()->updateOrCreate([
                        'code_id' => $gadd->code],[
                        'body' => $request->body
                    ]);

        if ($success){
            return 'oke';
        }
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
