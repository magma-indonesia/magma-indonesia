<?php

namespace App\Http\Controllers\Json\WOVOdat;

use Illuminate\Support\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\WOVOdat\DeformationStation as DS;

class RealtimeTiltJson extends Controller
{

    public function json(Request $request, $deformationStation = 21)
    {
        $data = $this->realtimeData($request, $deformationStation);

        return $data;
    }

    protected function realtimeData($request, $deformationStation)
    {
        $start = Carbon::parse($request->start);
        $end = Carbon::parse($request->start)->addSeconds(2)->format('Y-m-d H:i:s');
    
        $station = DS::select('ds_id','ds_code','ds_name','cn_id')
                        ->with([
                            'common_network' => function ($query) {
                                $query->select('cn_id','vd_id','cn_name');
                            },
                            'common_network.volcano' => function ($query) {
                                $query->select('vd_id','vd_name');
                            },
                            'tilt_realtime' => function($query) use ($deformationStation, $start, $end) {
                                $query->select('ds_id','dd_tlt_time','dd_tlt1 AS x_axis','dd_tlt2 as y_axis','dd_tlt_temp as temp')
                                    ->where('ds_id',$deformationStation)
                                    ->where('dd_tlt_time',$start)
                                    ->orderBy('dd_tlt_time');
                            },
                        ])
                        ->where('ds_id',$deformationStation)
                        ->first();

        $station = $station->tilt_realtime ? $this->transformData($station) : [];
        return $station;
    }

    protected function transformData($station)
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
