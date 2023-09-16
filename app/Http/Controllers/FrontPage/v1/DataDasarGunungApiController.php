<?php

namespace App\Http\Controllers\FrontPage\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\v1\Gadd;
use Illuminate\Support\Facades\Cache;

class DataDasarGunungApiController extends Controller
{
    public function index()
    {
        $gadds = Cache::remember('v1/home/data-dasar', 120, function () {
            return Gadd::select('ga_code', 'ga_nama_gapi', 'ga_prov_gapi', 'ga_lon_gapi', 'ga_lat_gapi', 'slug')
                ->orderBy('ga_nama_gapi')
                ->get();
        });

        return view('v1.home.data-dasar', [
            'gadds' => $gadds,
        ]);
    }
}
