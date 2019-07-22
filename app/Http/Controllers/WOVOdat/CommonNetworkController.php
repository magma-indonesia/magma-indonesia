<?php

namespace App\Http\Controllers\WOVOdat;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use App\WOVOdat\Volcano;

class CommonNetworkController extends Controller
{
    public function index()
    {
        $volcanoes = Cache::remember('wovodat.common-network', 360, function () {
            return Volcano::select('vd_id','vd_num','vd_name')
                    ->whereHas('common_network')
                    ->orderBy('vd_name')
                    ->with([
                        'common_network' => function($query) {
                            $query->select('cn_id','vd_id','cn_name','cn_type','cn_desc');
                        },
                        'common_network.deformation_stations' => function($query) {
                            $query->select('cn_id','ds_id','ds_code','ds_name','ds_perm','ds_nlat','ds_nlon','ds_nelev','ds_rflag','ds_desc');
                        },
                        'common_network.fields_stations' => function($query) {
                            $query->select('cn_id','fs_id','fs_code','fs_name','fs_inst','fs_utc','fs_lat','fs_lon','fs_elev','fs_stime','fs_desc');
                        },
                        'common_network.gas_stations' => function($query) {
                            $query->select('cn_id','gs_id','gs_code','gs_name','gs_inst','gs_type','gs_utc','gs_lat','gs_lon','gs_elev','gs_stime','gs_desc');
                        },
                        'common_network.hydrologic_stations' => function($query) {
                            $query->select('cn_id','hs_id','hs_code','hs_name','hs_type','hs_utc','hs_lat','hs_lon','hs_elev','hs_stime','hs_desc');
                        },
                        'common_network.meteo_stations' => function($query) {
                            $query->select('cn_id','ms_id','ms_code','ms_name','ms_type','ms_utc','ms_lat','ms_lon','ms_elev','ms_stime','ms_desc');
                        }
                    ])
                    ->withCount(['deformation_stations','fields_stations','gas_stations','hydrologic_stations','meteo_stations','thermal_stations'])
                    ->get();
                });
        return view('wovodat.common-network.index', compact('volcanoes'));
    }
}
