<?php

namespace App\Http\Controllers\Api\Winston;

use App\Gadd;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Seismometer;
use Illuminate\Support\Facades\Cache;
use Intervention\Image\Facades\Image;
use Karlmonson\Ping\Facades\Ping;

class HelicorderController extends Controller
{
    public function index()
    {
        $health = Ping::check(config('app.winston_url'));

        if ($health !== 200) {
            return response()->json([
                'status' => false,
                'message' => 'Server Seismik sedang offline'
            ], 500);
        }

        return Cache::remember('gadd_has_seismometer', 60, function () {
            return Gadd::has('seismometers')
                ->with('seismometers')
                ->select('name', 'code', 'tzone')
                ->orderBy('name')
                ->get();
        });
    }

    public function show($scnl, Request $request)
    {
        $health = Ping::check(config('app.winston_url'));

        if ($health !== 200) {
            return response()->json([
                'status' => false,
                'message' => 'Server Seismik sedang offline'
            ], 500);
        }

        $seismometer = Seismometer::where('scnl', $scnl)->first();

        if (!$seismometer) {
            return [
                'status' => false,
                'message' => 'Stasiun tidak ditemukan'
            ];
        }

        $time = $request->input('t1', -2);
        $width = $request->input('w', 1200);
        $height = $request->input('h', 720);
        $label = $request->input('lb', 1);
        $timezone = $request->input('tz', 'Asia/Jakarta');

        $url =
        config('app.winston_url') . ':' . config('app.winston_port') . '/heli?code=' . $scnl . '&w='.$width.'&h='. $height.'&lb='. $label.'&tz='.$timezone.'&t1='.$time;

        try {
            $seismometer->increment('hit');
            return Image::make($url)->response();
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Server Seismik sedang mati'
            ], 500);
        }
    }
}
