<?php

namespace App\Http\Controllers\WOVOdat;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;
use App\WOVOdat\Volcano;
use App\WOVOdat\StationSeismic as Station;
use App\WOVOdat\IntervalSwarm as IS;

use App\Traits\WarnaGempa;

class IntervalSwarmController extends Controller
{

    use WarnaGempa;

    private $series = [];

    public function index()
    {
        $volcanoes = Volcano::select('vd_id','vd_name')
                ->with([
                    'seismic_network' => function($query) {
                        $query->select('sn_id','vd_id','sn_code','sn_name')
                            ->whereHas('swarm');
                    },
                    'stations' => function($query) {
                        $query->select('vd_id','ss_id','ss_code','ss_name')
                            ->whereHas('swarm')
                            ->withCount('swarm');
                    }
                ])
                ->whereHas('swarm')
                ->orderBy('vd_name')
                ->get();

        return view('wovodat.interval-swarm.index',compact('volcanoes'));
    }

    public function store(Request $request)
    {
        $start = Carbon::parse($request->start)->formatLocalized('%A, %d %B %Y');
        $end = Carbon::parse($request->end)->formatLocalized('%A, %d %B %Y');

        $volcano = IS::where('ss_id',$request->station)
                        ->with([
                            'network' => function ($query) {
                                $query->select('sn_id','vd_id');
                            },
                            'network.volcano' => function ($query) {
                                $query->select('vd_id','vd_name');
                            },
                            'station'
                        ])->first();

        $volcano_name = $volcano->network->volcano->vd_name;
        $station_name = $volcano->station->ss_name;

        $swarm = Cache::remember('wovodat.seismic-interval:'.$request->station.':'.$request->start.':'.$request->end, 900, function () use ($request) {
            return IS::select(
                        'sn_id','ss_id','sd_ivl_stime','sd_ivl_etime',
                        'sd_ivl_eqtype','sd_ivl_nrec')
                    ->selectRaw('SUM(sd_ivl_nrec) AS jumlah')
                    ->selectRaw('DATE(sd_ivl_stime) AS date')
                    ->whereNotNull('sn_id')
                    ->where('ss_id',$request->station)
                    ->where('sd_ivl_stime','>=',$request->start)
                    ->where('sd_ivl_etime','<=',$request->end.' 23:59:59')
                    ->orderBy('date')
                    ->groupBy('sd_ivl_eqtype','date')
                    ->get();
        });

        $swarm = $swarm ? $this->transformSwarm($swarm) : [];

        return view('wovodat.interval-swarm.result',compact('swarm','volcano_name','station_name')) ;
    }

    protected function transformSwarm($swarm)
    {
        return $swarm->groupBy('eq_magma')
                ->values()
                ->transform(function ($item, $key) {
                    return [
                        'color' => $this->getColor($item[0]['eq_magma']),
                        'name' => $item[0]['eq_name'],
                        'data' => $item->transform(function ($item, $key) {
                            return [$item->unix_time, intval($item->jumlah)];
                        }),
                    ];
                });
    }
}
