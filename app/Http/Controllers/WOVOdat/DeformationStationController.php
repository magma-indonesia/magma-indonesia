<?php

namespace App\Http\Controllers\WOVOdat;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use App\Http\Controllers\Controller;
use App\WOVOdat\Volcano;

class DeformationStationController extends Controller
{
    public function index()
    {
        $volcanoes = Volcano::whereHas('deformation_stations')
                        ->with('information','deformation_stations.deformation_instrument')
                        ->select('vd_id','vd_name')
                        ->orderBy('vd_name')
                        ->get();

        return view('wovodat.deformation-station.index', compact('volcanoes'));
    }
}
