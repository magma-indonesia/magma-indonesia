<?php

namespace App\Http\Controllers\WOVOdat;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Builder;
use Carbon\Carbon;
use App\WOVOdat\Volcano;
use App\WOVOdat\StationSeismic as Stations;

class RsamController extends Controller
{

    private $series = [];
    private $rsam = [];

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
        ini_set('max_execution_time', 1200);

        $station = Stations::with([
                        'rsam_ssam' => function($query) use ($request) {
                            $query->whereHas('rsam', function(Builder $query)  use ($request) {
                                $query->where('sd_rsm_stime','>=',$request->start)
                                    ->where('sd_rsm_stime','<=',$request->end.' 23:59:59');
                            })
                            ->select('sd_sam_id','ss_id','sd_sam_stime','sd_sam_etime')
                            ->where('ss_id',$request->station);
                        },
                        'rsam_ssam.rsam' => function($query) use ($request) {
                            $query->select('sd_rsm_stime','sd_sam_id','sd_rsm_id','sd_rsm_count')
                                ->where('sd_rsm_stime','>=',$request->start)
                                ->where('sd_rsm_stime','<=',$request->end.' 23:59:59')
                                ->orderBy('sd_rsm_stime');
                        },
                        'network' => function($query) {
                            $query->select('sn_id','vd_id');
                        },
                        'network.volcano' => function($query) {
                            $query->select('vd_name','vd_id');
                        }
                    ])
                    ->select('ss_id','sn_id','ss_name')
                    ->where('ss_id',$request->station)
                    ->first();
        
        $volcano_name = $station->network->volcano->vd_name;
        $station_name = $volcano_name.' - '.$station->ss_name;
        $date = Carbon::parse($request->start)->formatLocalized('%d %B %Y').' - '.Carbon::parse($request->end)->formatLocalized('%d %B %Y');
        $rsam = $this->transformRsam($station);
        
        return view('wovodat.rsam.result', compact('rsam','volcano_name','station_name','date'));
    }

    protected function transformRsam($station)
    {
        $rsam_ssam = $station->rsam_ssam;

        if (empty($rsam_ssam))
        {
            return [];
        }

        $rsam_ssam->each(function ($item, $key) {
            $this->rsam[] = $item->rsam;
        });

        $rsam = collect($this->rsam)->flatten(1);

        return $rsam->transform(function ($item, $key) {
            $collection = collect($item)->forget(['sd_rsm_stime','sd_sam_id','sd_rsm_id']);
            return [$item->unix_time, $item->sd_rsm_count];
        });
    }
}
