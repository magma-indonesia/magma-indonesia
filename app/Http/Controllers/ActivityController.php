<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\SearchGunungApi;
use Carbon\Carbon;
use App\Gadd;
use App\MagmaVar;
use App\VarDaily;

class ActivityController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {     
        $gadds = Gadd::orderBy('name')->whereNotIn('code',['TEO','SBG'])->get();
        return view('activities.index',compact('gadds'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id, Request $request)
    {
        // return $request;
        $var = MagmaVar::where('noticenumber',$id)->with([

            'user:name,nip',
            'visual',
            'visual.asap',
            'klimatologi',
            'gempa'
            
            ])->first();

        return $var;

    }
}
