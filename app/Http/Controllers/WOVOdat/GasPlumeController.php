<?php

namespace App\Http\Controllers\WOVOdat;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use App\WOVOdat\GasPlume;
use App\WOVOdat\Volcano;
use App\WOVOdat\GasStation;
use Illuminate\Support\Carbon;

class GasPlumeController extends Controller
{

    public function index()
    {
        $volcanoes = Volcano::whereHas('gas_stations')
                        ->with('gas_stations:gs_id,gs_code,gs_name,vd_id')
                        ->select('vd_id','vd_name')
                        ->orderBy('vd_name')
                        ->get();

        return view('wovodat.gas-station.plume.index', compact('volcanoes'));
    }

    public function store(Request $request) 
    {
        $station = Cache::remember('wovodat.common-network.deformation-stations:gas.'.$request->station.':'.$request->start.':'.$request->end, 360, function () use ($request) {
            return GasStation::select('gs_id','gs_code','gs_name','cn_id')
                    ->with([
                        'common_network' => function ($query) {
                            $query->select('cn_id','vd_id','cn_name');
                        },
                        'common_network.volcano' => function ($query) {
                            $query->select('vd_id','vd_name');
                        },
                        'plume' => function($query) use ($request) {
                            $query->select('gs_id','gd_plu_time','gd_plu_emit')
                                ->where('gs_id',$request->station)
                                ->where('gd_plu_time','>=',$request->start)
                                ->where('gd_plu_time','<=',$request->end.' 23:59:59');
                        },
                    ])
                    ->where('gs_id',$request->station)
                    ->first();
        });

        $volcano = $station->common_network->volcano->vd_name;
        $station_name = $volcano.' - '.$station->gs_name;
        $date = Carbon::parse($request->start)->formatLocalized('%d %B %Y').' - '.Carbon::parse($request->end)->formatLocalized('%d %B %Y');

        $plume = $this->transformData($station);

        return view('wovodat.gas-station.plume.result', compact('plume','volcano','station_name','date'));
    }

    protected function transformData($station)
    {
        $plume = $station->plume;

        if (empty($plume))
            return [];

        return $plume->transform(function ($item, $key) {
            return [$item->unix_time, $item->gd_plu_emit];
        });
    }
}
