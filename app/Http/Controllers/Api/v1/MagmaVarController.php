<?php

namespace App\Http\Controllers\Api\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\v1\MagmaVar;
use App\v1\MagmaVarOptimize;
use App\v1\Gadd;
use App\Http\Resources\v1\MagmaVarResource;
use App\Http\Resources\v1\MagmaVarCollection;

class MagmaVarController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $vars = Gadd::with('var')->paginate(5);
        return new MagmaVarCollection($vars);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Vona  $vona
     * @return \Illuminate\Http\Response
     */
    public function show($code, $noticenumber = null)
    {
        if ($noticenumber) {
            $var = MagmaVarOptimize::where('ga_code',$code)
                    ->where('var_noticenumber',$noticenumber)
                    ->firstOrFail();
            
            return new MagmaVarResource($var);
        }

        $var = MagmaVarOptimize::where('ga_code',$code)
                    ->orderBy('var_data_date','desc')
                    ->orderBy('periode','desc')
                    ->first();

        return new MagmaVarResource($var);
    }
}
