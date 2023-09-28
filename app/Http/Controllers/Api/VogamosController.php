<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Vogamos\VogamosData;
use App\Vogamos\VogamosStation;
use Illuminate\Support\Collection;

class VogamosController extends Controller
{
    public function index(Request $request)
    {
        return VogamosStation::where('code', $request->code)
            ->where('is_active', 1)->get();
    }

    public function store(Request $request)
    {
        $stations = collect($request->toArray())->each(function ($station) {
            VogamosStation::updateOrCreate([
                'station_id' => $station['station_id'],
            ], [
                'code' => $station['code'],
                'station' => $station['station'],
                'nama' => $station['nama'],
                'dusun' => $station['dusun'],
                'desa' => $station['desa'],
                'kecamatan' => $station['kecamatan'],
                'kabupaten' => $station['kabupaten'],
                'provinsi' => $station['provinsi'],
                'elevation' => $station['elevation'],
                'latitude' => $station['latitude'],
                'longitude' => $station['longitude'],
                'keterangan' => $station['keterangan'],
                'is_active' => $station['is_active'],
            ]);
        });

        return VogamosStation::all();
    }
}
