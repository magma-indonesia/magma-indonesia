<?php

namespace App\Http\Controllers\Api\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\v1\MagmaVen;
use Illuminate\Support\Facades\URL;
use App\Http\Resources\v1\MagmaVenCollection;
use App\Http\Resources\v1\MagmaVenResource;
use Illuminate\Support\Facades\Cache;

class MagmaVenController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $ven = MagmaVen::select('erupt_id')->orderBy('erupt_tgl', 'desc')
            ->orderBy('erupt_jam', 'desc')->first();

        $vens = Cache::remember('v1/api/letusan-show' . $ven->erupt_id, 10, function () {
            return MagmaVen::with('user','gunungapi:ga_code,ga_zonearea,ga_nama_gapi,ga_lat_gapi,ga_lon_gapi,ga_elev_gapi')
                ->orderBy('erupt_tgl', 'desc')
                ->orderBy('erupt_jam', 'desc')
                ->paginate(15);
        });

        return new MagmaVenCollection($vens);
    }

    public function latest()
    {
        $ven = MagmaVen::with('user', 'gunungapi:ga_code,ga_zonearea,ga_nama_gapi,ga_lat_gapi,ga_lon_gapi,ga_elev_gapi')
        ->orderBy('erupt_tgl', 'desc')
        ->orderBy('erupt_jam', 'desc')
        ->first();

        return new MagmaVenResource($ven);
    }
}
