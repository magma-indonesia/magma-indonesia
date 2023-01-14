<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Vogamos\VogamosData;
use App\Vogamos\VogamosStation;

class VogamosController extends Controller
{
    public function index()
    {
        return [];
    }

    public function store(Request $request)
    {
        // $this->groupByStation->firstOrCreateStation->insertDataPerStation;

        $station = VogamosStation::where('ID_stasiun', $request->ID_stasiun)
            ->firstOrFail();

        $data = (new VogamosData())->setTable(strtoupper($request->ID_stasiun));

        $data = $data->updateOrCreate($request->only([
            'ID_stasiun', 'date_data'
        ]), $request->only($station->channels_used->toArray()));

        return $data;
    }
}
