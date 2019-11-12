<?php

namespace App\Http\Controllers\Api\WOVOdat;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\WOVOdat\DeformationStation as DS;

class DeformationTiltController extends Controller
{
    public function realtime(Request $request, $deformationStation = 21)
    {
        return $this->realtimeData($deformationStation); 
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
                            'tilt_realtime' => function($query) use ($deformationStation) {
                                $query->select('ds_id','dd_tlt_time','dd_tlt1 AS x_axis','dd_tlt2 as y_axis','dd_tlt_temp as temp')
                                    ->where('ds_id',$deformationStation)
                                    ->where('dd_tlt_time','>=', now()->subSeconds(60)->format('Y-m-d H:i:s'))
                                    ->where('dd_tlt_time','<=', now()->format('Y-m-d H:i:s'))
                                    ->orderBy('dd_tlt_time');
                            },
                        ])
                        ->where('ds_id',$deformationStation)
                        ->first();

        $volcano = $station->common_network->volcano->vd_name;
        $network = $station->common_network->cn_name;
        $station_name = $station->ds_name;

        $station = $station->tilt_realtime ? $this->transformRealtimeData($station) : [];

        return [
            'station' => $station,
            'volcano' => $volcano,
            'network' => $network,
            'station_name' => $station_name,
        ];
    }

    protected function transformRealtimeData($station)
    {

        $x = $station->tilt_realtime->map(function ($item,$key) {
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

        $y = $station->tilt_realtime->map(function ($item,$key) {
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

        $temp = $station->tilt_realtime->map(function ($item,$key) {
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
