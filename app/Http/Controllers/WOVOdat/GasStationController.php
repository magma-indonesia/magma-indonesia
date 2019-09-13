<?php

namespace App\Http\Controllers\WOVOdat;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\WOVOdat\Volcano;

class GasStationController extends Controller
{
    public function index()
    {
        $volcanoes = Volcano::whereHas('gas_stations')
                        ->with([
                            'information',
                            'gas_stations' => function ($query) {
                                $query->whereNotNull('gs_lat')
                                    ->whereNotNull('gs_lon');
                            }
                        ])
                        ->select('vd_id','vd_name')
                        ->orderBy('vd_name')
                        ->get();

        return view('wovodat.gas-station.index', compact('volcanoes'));
    }
}
