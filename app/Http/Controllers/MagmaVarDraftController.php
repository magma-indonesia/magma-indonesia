<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\DraftMagmaVar as Draft;

class MagmaVarDraftController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $drafts = Draft::paginate(30);
        return view('gunungapi.laporan.draft.index',compact('drafts'));
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
     * @param  str $noticenumber
     * @return \Illuminate\Http\Response
     */
    public function show($noticenumber)
    {
        $draft = Draft::findOrFail($noticenumber);

        $var = $draft->var;
        $var['gunung_api'] = $draft->gunungapi;

        session([
            'var' => $var,
            'var_visual' => $draft->var_visual,
            'var_klimatologi' => $draft->var_klimatologi,
            'var_gempa' => $draft->var_gempa
        ]);
        
        return redirect()->route('chambers.laporan.preview.magma.var');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
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
     * @param  str $noticenumber
     * @return \Illuminate\Http\Response
     */
    public function destroy($noticenumber)
    {
        $draft = Draft::findOrFail($noticenumber);
        if ($draft->delete())
        {
            $data = [
                'success' => 1,
                'message' => 'Berhasil dihapus.'
            ];

            return response()->json($data);
        }

        $data = [
            'success' => 0,
            'message' => 'Gagal dihapus.'
        ];

        return response()->json($data);
    }
}
