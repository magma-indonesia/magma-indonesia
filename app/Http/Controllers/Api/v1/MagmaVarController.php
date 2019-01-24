<?php

namespace App\Http\Controllers\Api\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\v1\MagmaVar;
use App\v1\Gadd;
use App\Http\Resources\v1\MagmaVarResource;
use App\Http\Resources\v1\MagmaVarCollection;

class MagmaVarController extends Controller
{
    public function __construct()
    {
        ini_set('max_execution_time', 60);
    }

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
            $var = MagmaVar::where('ga_code',$code)
                    ->where('var_noticenumber',$noticenumber)
                    ->firstOrFail();
            
            return new MagmaVarResource($var);
        }

        $var = MagmaVar::where('ga_code',$code)
                    ->orderBy('var_data_date','desc')
                    ->orderBy('periode','desc')
                    ->first();

        return new MagmaVarResource($var);
    }
}
