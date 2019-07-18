<?php

namespace App\Http\Controllers\WOVOdat;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\WOVOdat\Volcano;
use App\WOVOdat\SeismicNetwork;

class SeismicEventController extends Controller
{
    public function create()
    {
        $volcanoes = Volcano::select('vd_id','vd_name')
                        ->with([
                            'seismic_network' => function($query) {
                                $query->whereHas('events')
                                    ->select('sn_id','vd_id','sn_code','sn_name');
                            },
                        ])
                        ->whereHas('events')
                        ->withCount('events')
                        ->orderBy('vd_name')
                        ->get();

        return view('wovodat.event.create', compact('volcanoes'));
    }

    public function store(Request $request)
    {
        ini_set('max_execution_time', 120);

        $network = SeismicNetwork::select('sn_id','sn_code','vd_id','sn_name')
                    ->with([
                        'volcano' => function($query) {
                            $query->select('vd_id','vd_name');
                        },
                        'volcano.information' => function($query) {
                            $query->select('vd_id','vd_inf_slat','vd_inf_slon');
                        },
                        'events' => function($query) use ($request) {
                            $query->select('sn_id','sd_evn_time','sd_evn_dur','sd_evn_elat','sd_evn_elon','sd_evn_edep','sd_evn_pmag')
                                ->whereNotNull('sd_evn_elat')
                                ->whereNotNull('sd_evn_elon')
                                ->where('sn_id', $request->network)
                                ->where('sd_evn_time','>=',$request->start)
                                ->where('sd_evn_time','<=',$request->end.' 23:59:59')
                                ->orderBy('sd_evn_time');
                        },
                    ])
                    ->where('sn_id',$request->network)
                    ->first();

        return view('wovodat.event.result', compact('network'));
    }
}
