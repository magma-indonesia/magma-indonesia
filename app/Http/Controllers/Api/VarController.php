<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\SearchGunungApi;
use Carbon\Carbon;
use App\Gadd;
use App\MagmaVar;
use App\VarDaily;
use App\Http\Resources\VarResource;
use App\Http\Resources\VarCollection;
use App\Http\Resources\GunungApiCollection;
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

        Carbon::setLocale('id');             
        return new VarResource($var);
            
        // return $var;
        
        return view('gunungapi.laporan.show', compact('var'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function latest()
    {
        $gadds = Gadd::orderBy('name')
                    ->whereNotIn('code',['TEO','SBG'])
                    ->get();
            
        return $vars = new LatestVarCollection($gadds);
        
    }
}