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
use Illuminate\Support\Facades\DB;

class MagmaVarController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $last_var = OldVar::select('no','var_log')->orderBy('no','desc')->first();

        $gadds = Cache::remember('v1/home/gadd', 120, function() {
            return Gadd::select(
                'ga_code','ga_nama_gapi','ga_kab_gapi',
                'ga_prov_gapi','ga_koter_gapi','ga_elev_gapi',
                'ga_lon_gapi','ga_lat_gapi','ga_status')
            ->whereNotIn('ga_code',['TEO'])
            ->orderBy('ga_nama_gapi','asc')
            ->get();
        });

        $ga_code = $gadds->pluck('ga_code');

        $vars = Cache::remember('v1/home/var:'.strtotime($last_var->var_log), 60, function() use($ga_code) {
            return OldVar::select(DB::raw('t.*'))
                ->from(DB::raw('(SELECT ga_code,cu_status,var_data_date,periode,var_perwkt,var_noticenumber,var_nama_pelapor FROM magma_var ORDER BY var_data_date DESC, periode DESC ) t'))
                ->whereIn('ga_code',$ga_code)
                ->groupBy('t.ga_code')
                ->get();
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
