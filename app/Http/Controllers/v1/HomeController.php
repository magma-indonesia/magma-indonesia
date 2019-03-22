<?php

namespace App\Http\Controllers\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use App\v1\Gadd;
use App\v1\MagmaVar as OldVar;
use App\v1\PosPga;
use App\v1\GertanCrs as Crs;
use App\v1\MagmaRoq as Roq;
use App\v1\MagmaSigertan;
use DB;

class HomeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $gadds = Cache::remember('v1/home/gadd', 120, function() {
            return Gadd::select(
                'ga_code','ga_nama_gapi','ga_kab_gapi',
                'ga_prov_gapi','ga_koter_gapi','ga_elev_gapi',
                'ga_lon_gapi','ga_lat_gapi','ga_status')
            ->whereNotIn('ga_code',['TEO','SBG'])
            ->orderBy('ga_nama_gapi','asc')
            ->get();
        });

        $ga_code = $gadds->pluck('ga_code');

        $vars = OldVar::select(DB::raw('t.*'))
                        ->from(DB::raw('(SELECT ga_code,cu_status,var_data_date,periode,var_perwkt,var_noticenumber,var_nama_pelapor FROM magma_var ORDER BY var_noticenumber DESC) t'))
                        ->whereIn('ga_code',$ga_code)
                        ->groupBy('t.ga_code')
                        ->get();

        $gadds = $gadds->map(function ($gadd, $key) use($vars) {
            $var = $vars->where('ga_code',$gadd->ga_code)->first();
            $gadd->ga_status = $var->cu_status;
            return $gadd;
        });

        $gertans = Cache::remember('v1/home/sigertan', 10, function() {
            return Crs::has('tanggapan')->with('tanggapan')->whereBetween('crs_lat',[-12, 2])
                    ->whereBetween('crs_lon',[89, 149])
                    ->orderBy('crs_log','desc')
                    ->limit(30)
                    ->get();
        });

        $gempas = Cache::remember('v1/home/gempa',10, function() {
            return Roq::orderBy('datetime_wib','desc')->limit(30)->get();
        });
        
        return view('v1.home.index',compact('gadds','gertans','gempas'));
    }
}
