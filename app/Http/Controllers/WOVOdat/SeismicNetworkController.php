<?php

namespace App\Http\Controllers\WOVOdat;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use App\WOVOdat\Volcano;
use App\WOVOdat\SeismicNetwork as SN;

class SeismicNetworkController extends Controller
{
    public function index(Request $request)
    {
        $page = $request->has('page') ? $request->page : 1;

        $volcanoes = Cache::remember('wovodat.seismic-network:'.$page, 1440, function() {
            return Volcano::whereHas('seismic_network.stations')
                    ->orderBy('vd_name')
                    ->with('seismic_network.stations')
                    ->paginate(10);
        });

        return view('wovodat.seismic-newtork.index', compact('volcanoes'));

    }
}
