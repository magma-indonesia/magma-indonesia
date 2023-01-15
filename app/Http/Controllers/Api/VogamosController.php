<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Vogamos\VogamosData;
use App\Vogamos\VogamosStation;
use Illuminate\Support\Collection;

class VogamosController extends Controller
{
    public function index()
    {
        return [];
    }

    protected function sortDateByDescThenGroupedByStation(Request $request): array
    {
        return collect($request->toArray())
                ->sortByDesc('date_data')
                ->groupBy('ID_stasiun')
                ->all();
    }

    public function store(Request $request)
    {
        $stations = $this->sortDateByDescThenGroupedByStation($request);

        [$stationsExists, $stationsNotExists] = collect($stations)->partition(function ($stationData, $stationId) {
            return VogamosStation::where('ID_stasiun', $stationId)->exists();
        });

        return $stationsExists;

        // $stationsNotExists = collect($stations)->keys()->reject(function ($station) {
        //     return VogamosStation::where('ID_stasiun', $station)->exists();
        // })->values();

        // return $stationsNotExists;
        // $this->groupByStation->firstOrCreateStation->insertDataPerStation->updateLastUpdate;

        $station = VogamosStation::firstOrCreate([
            'ID_stasiun' => strtoupper($request->ID_stasiun)
        ]);

        $data = (new VogamosData())->setTable(strtoupper($request->ID_stasiun));

        $data = $data->updateOrCreate($request->only([
            'ID_stasiun', 'date_data'
        ]), $request->only($station->channels_used->toArray()));

        return $data;
    }
}
