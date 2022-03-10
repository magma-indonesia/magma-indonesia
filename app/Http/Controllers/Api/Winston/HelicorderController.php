<?php

namespace App\Http\Controllers\Api\Winston;

use App\Gadd;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Seismometer;
use Intervention\Image\Facades\Image;

class HelicorderController extends Controller
{
    public function index()
    {
        return Gadd::has('seismometers')
            ->with('seismometers')
            ->select('name','code','tzone')
            ->get();
    }

    public function show($scnl, Request $request)
    {
        if (!Seismometer::where('scnl',$scnl)->first()) {
            return [
                'status' => false,
                'message' => 'Stasiun tidak ditemukan'
            ];
        }

        $time = $request->input('t1', -2);
        $width = $request->input('w', 720);
        $height = $request->input('h', 320);
        $label = $request->input('lb', 1);
        $timezone = $request->input('tz', 'Asia/Jakarta');

        $url =
        config('app.winston_url') . ':' . config('app.winston_port') . '/heli?code=' . $scnl . '&w='.$width.'&h='. $height.'&lb='. $label.'&tz='.$timezone.'&t1='.$time;

        try {
            return Image::make($url)->response();
        } catch (\Throwable $th) {
            abort(404,'Tidak ditemukan.');
        }
    }
}
