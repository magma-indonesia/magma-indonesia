<?php

namespace App\Http\Controllers\FrontPage\v1;

use App\GerakanTanah\LewsData;
use App\GerakanTanah\LewsStation;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Collection;

class LewsController extends Controller
{
    protected function categories(Collection $data): array
    {
        return $data->sortBy('date_data')->pluck('date_data')->transform(function ($date) {
            return $date->format('Y-m-d H:i:s');
        })->toArray();
    }

    protected function series(LewsStation $lewsStation, Collection $data)
    {
        return $lewsStation->channels_used->transform(function ($channelUsed) use ($lewsStation, $data) {
            return [
                'alias' => $channelUsed,
                'name' => $lewsStation[$channelUsed],
                'data' => $data->pluck($channelUsed),
            ];
        });
    }

    public function index()
    {
        return view('v1.home.lews-index', [
            'stations' => LewsStation::all(),
        ]);
    }

    public function show(int $id)
    {
        $station = LewsStation::findOrFail($id);

        $data = (new LewsData())->setTable($station->ID_stasiun);
        $lastDate = $data->orderBy('date_data', 'desc')->first()['date_data'];

        if (is_null($lastDate)) {
            abort(404);
        }

        $data = $data->orderBy('date_data', 'desc')
            ->whereBetween('date_data', [$lastDate->copy()->subDays(3), $lastDate])
            ->get($station->channels_used->merge(['ID', 'date_data'])->toArray());

        return view('v1.home.lews-show', [
            'station_name' => "$station->nama_kabupaten, $station->nama_kecamatan, $station->nama_desa, $station->nama_dusun",
            'data' => [
                'categories' => $this->categories($data),
                'series' => $this->series($station, $data),
            ],
            'station' => $station,
        ]);

    }
}
