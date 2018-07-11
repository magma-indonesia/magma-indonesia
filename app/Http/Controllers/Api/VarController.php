<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\VarDaily;
use App\MagmaVar;
use App\Http\Resources\VarResource;
use App\Http\Resources\LatestVarCollection;

class VarController extends Controller 
{
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $var = MagmaVar::where('noticenumber',$id)->first();      
        return new VarResource($var);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function latest()
    {
        $latest = VarDaily::orderBy('code_id')->get();
        return new LatestVarCollection($latest);
    }
}