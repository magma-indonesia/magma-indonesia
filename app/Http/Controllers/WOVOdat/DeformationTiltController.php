<?php

namespace App\Http\Controllers\WOVOdat;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use App\WOVOdat\Volcano;
use App\WOVOdat\DeformationStation as DS;

class DeformationTiltController extends Controller
{
    public function index()
    {
        $volcanoes = Volcano::whereHas('deformation_stations')
                        ->with('deformation_stations:ds_id,ds_code,ds_name,vd_id')
                        ->select('vd_id','vd_name')
                        ->orderBy('vd_name')
                        ->get();

        return view('wovodat.deformation-station.tilt.index', compact('volcanoes'));
    }

    public function store(Request $request)
    {
        $station = Cache::remember('wovodat.common-network.deformation-stations:'.$request->station.':'.$request->start.':'.$request->end, 360, function () use ($request) {
            return DS::select('ds_id','ds_code','ds_name','cn_id')
                    ->with([
                        'common_network' => function ($query) {
                            $query->select('cn_id','vd_id','cn_name');
                        },
                        'common_network.volcano' => function ($query) {
                            $query->select('vd_id','vd_name');
                        },
                        'tilt' => function($query) use ($request) {
                            $query->select('ds_id','dd_tlt_time','dd_tlt1 AS x_axis','dd_tlt2 as y_axis','dd_tlt_temp as temp')
                                ->where('ds_id',$request->station)
                                ->where('dd_tlt_time','>=',$request->start)
                                ->where('dd_tlt_time','<=',$request->end.' 23:59:59');
                        },
                    ])
                    ->where('ds_id',$request->station)
                    ->first();
        });
        
        $volcano = $station->common_network->volcano->vd_name;
        $network = $station->common_network->cn_name;
        $station_name = $station->ds_name;
        $date = Carbon::parse($request->start)->formatLocalized('%d %B %Y').' - '.Carbon::parse($request->end)->formatLocalized('%d %B %Y');

        $station = $station->tilt ? $this->transformData($station) : [];

        return view('wovodat.deformation-station.tilt.result', compact('station','volcano','network','station_name','date'));
    }

    protected function realtimeData($deformationStation)
    {
        $station = DS::select('ds_id','ds_code','ds_name','cn_id')
                        ->with([
                            'common_network' => function ($query) {
                                $query->select('cn_id','vd_id','cn_name');
                            },
                            'common_network.volcano' => function ($query) {
                                $query->select('vd_id','vd_name');
                            },
                            'tilt' => function($query) use ($deformationStation) {
                                $query->select('ds_id','dd_tlt_time','dd_tlt1 AS x_axis','dd_tlt2 as y_axis','dd_tlt_temp as temp')
                                    ->where('ds_id',$deformationStation)
                                    ->where('dd_tlt_time','>=','2013-11-06 13:00:00')
                                    ->where('dd_tlt_time','<=','2013-11-06 23:59:59')
                                    ->orderBy('dd_tlt_time');
                            },
                        ])
                        ->where('ds_id',$deformationStation)
                        ->first();

        $volcano = $station->common_network->volcano->vd_name;
        $network = $station->common_network->cn_name;
        $station_name = $station->ds_name;

        $station = $station->tilt ? $this->transformData($station) : [];

        return [
            'station' => $station,
            'volcano' => $volcano,
            'network' => $network,
            'station_name' => $station_name,
        ];
    }

    public function realtime(Request $request, $deformationStation = 21)
    {
        $data = $this->realtimeData($deformationStation);
        $station = $data['station'];
        $volcano = $data['volcano'];
        $network = $data['network'];
        $station_name = $data['station_name'];
        
        return view('wovodat.deformation-station.tilt.realtime', compact('station','volcano','network','station_name'));
    }

    protected function transformData($station)
    {

        $x = $station->tilt->map(function ($item,$key) {
            return [
                $item->unix_time,
                $item->x_axis
            ];
        });

        $data[] = [
            'name' => 'Sumbu X',
            'data' => $x,
            'unit' => 'mm',
            'type' => 'line',
            'decimal' => 1
        ];

        $y = $station->tilt->map(function ($item,$key) {
            return [
                $item->unix_time,
                $item->y_axis
            ];
        });

        $data[] = [
            'name' => 'Sumbu Y',
            'data' => $y,
            'unit' => 'mm',
            'type' => 'line',
            'decimal' => 1
        ];

        $temp = $station->tilt->map(function ($item,$key) {
            return [
                $item->unix_time,
                $item->temp
            ];
        });

        $data[] = [
            'name' => 'Temperature',
            'data' => $temp,
            'unit' => '°C',
            'type' => 'line',
            'decimal' => 1
        ];

        return $data;
    }
}
