<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Vogamos\VogamosData;

class VogamosController extends Controller
{
    public function index()
    {
        return [];
    }

    public function store(Request $request)
    {
        $data = (new VogamosData())->setTable($request->ID_stasiun);

        $data->firstOrCreate([
            'ID_stasiun' => $request->ID_stasiun,
            'date_data' => $request->date_data,
        ], [
            'channel_0' => $request->channel_0,
            'channel_1' => $request->channel_1,
            'channel_2' => $request->channel_2,
            'channel_3' => $request->channel_3,
            'channel_4' => $request->channel_4,
            'channel_5' => $request->channel_5,
            'channel_6' => $request->channel_6,
            'channel_7' => $request->channel_7,
            'channel_8' => $request->channel_8,
            'channel_9' => $request->channel_9,
            'channel_10' => $request->channel_10,
            'channel_11' => $request->channel_11,
            'channel_12' => $request->channel_12,
            'channel_13' => $request->channel_13,
            'channel_14' => $request->channel_14,
            'channel_15' => $request->channel_15,
        ]);

        return $data;
    }
}
