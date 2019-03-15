<?php

namespace App\Http\Controllers\Api\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\v1\MagmaVar as OldVar;
use App\v1\MagmaVarOptimize;
use App\v1\Gadd;
use App\Http\Resources\v1\MagmaVarResource;
use App\Http\Resources\v1\MagmaVarCollection;
use Illuminate\Support\Facades\Cache;

class MagmaVarController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $last = OldVar::select('no')->orderBy('no','desc')->first();
        $page = $request->has('page') ? $request->page : 1;

        $vars = Cache::remember('v1/api/vars-'.$last->no.'-page-'.$page, 60, function() {
            return Gadd::with('var')->paginate(5);
        });
        
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
            $var = Cache::remember('v1/api/var-show-'.$code.$noticenumber, 60, function() use($code,$noticenumber) {
                return MagmaVarOptimize::where('ga_code',$code)
                    ->where('var_noticenumber',$noticenumber)
                    ->firstOrFail();
            });
            
            return new MagmaVarResource($var);
        }

        $var = Cache::remember('v1/api/var-show-'.$code, 60, function () use($code) {
            return MagmaVarOptimize::where('ga_code',$code)
                ->orderBy('var_data_date','desc')
                ->orderBy('periode','desc')
                ->first();
        });

        return new MagmaVarResource($var);
    }
}
