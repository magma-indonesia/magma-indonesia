<?php

namespace App\Http\Controllers\WOVOdat;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Builder;
use App\WOVOdat\Volcano;
use App\WOVOdat\StationSeismic as Stations;

class RsamController extends Controller
{
    public function index()
    {
        return $volcanoes = Volcano::whereHas('stations')
                                ->with('stations')
                                ->select('vd_id','vd_name')
                                ->get();

        return view('wovodat.rsam.index');
    }

    public function create()
    {
        $volcanoes = Volcano::whereHas('stations')
                        ->with('stations')
                        ->orderBy('vd_name')
                        ->select('vd_id','vd_name')
                        ->get();

        return view('wovodat.rsam.create', compact('volcanoes'));
    }

    public function store(Request $request)
    {
        return Stations::with([
                'rsam_ssam' => function($query) use ($request) {
                    $query->whereHas('rsam', function(Builder $query)  use ($request) {
                        $query->where('sd_rsm_stime','>=',$request->start)
                            ->where('sd_rsm_stime','<=',$request->end.' 23:59:59');
                    })
                    ->select('sd_sam_id','ss_id')
                    ->where('ss_id',$request->station);
                },
                'rsam_ssam.rsam' => function($query) use ($request) {
                    $query->select('sd_rsm_stime','sd_sam_id','sd_rsm_id','sd_rsm_count')
                        ->where('sd_rsm_stime','>=',$request->start)
                        ->where('sd_rsm_stime','<=',$request->end.' 23:59:59')
                        ->orderBy('sd_rsm_stime');
                }
            ])
        ->select('ss_id','sn_id','ss_name')
        ->where('ss_id',$request->station)
        ->first();
    }
}
