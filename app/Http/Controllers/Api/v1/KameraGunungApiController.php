<?php

namespace App\Http\Controllers\Api\v1;

use App\Gadd;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\KameraGunungApi;
use Illuminate\Support\Facades\Cache;
use Karlmonson\Ping\Facades\Ping;
use Intervention\Image\Facades\Image;

class KameraGunungApiController extends Controller
{
    protected function gadd()
    {
        return Cache::remember('gadd_kamera', 60, function () {
            return Gadd::select('code', 'name')
                ->whereHas('cctv', function ($query) {
                    $query->where('publish', 1);
                })
                ->withCount('cctv')
                ->get();
        });
    }

    protected function filteredCCTV($code)
    {
        return Cache::remember("kamera_gunungapi:{$code}", 60, function () use ($code) {
            return KameraGunungApi::with('gunungapi:code,name')
                ->where('publish', 1)
                ->where('code', $code)
                ->orderBy('code')
                ->get()
                ->makeHidden(['full_url']);
        });
    }

    protected function nonFilteredCCTV()
    {
        return Cache::remember('kamera_gunungapi', 60, function () {
            return KameraGunungApi::with('gunungapi:code,name')
                ->where('publish', 1)
                ->orderBy('code')
                ->get()
                ->makeHidden(['full_url']);
        });
    }

    public function index()
    {
        $health = Ping::check(config('app.cctv_url'));

        if ($health == 200) {
            $gadds = $this->gadd();

            $cctvs = $this->nonFilteredCCTV();

            if ($cctvs->isEmpty())
                abort(404);

            return [
                'cctvs' => $cctvs,
                'gunung_api' => $gadds
            ];
        }

        return response()->json([
            'status' => false,
            'message' => 'Server CCTV sedang offline'
        ], 500);
    }

    public function filter($code)
    {
        $health = Ping::check(config('app.cctv_url'));

        if ($health == 200) {
            $cctvs = $this->filteredCCTV($code);

            if ($cctvs->isEmpty())
                abort(404);

            return [
                'cctvs' => $cctvs,
            ];
        }

        return response()->json([
            'status' => false,
            'message' => 'Server CCTV sedang offline'
        ], 500);
    }

    public function show(Request $request)
    {
        $health = Ping::check(config('app.cctv_url'));

        if ($health == 200) {
            $cctv = KameraGunungApi::where('publish', 1)
                ->where('uuid', $request->uuid)
                ->firstOrFail();

            $cctv->increment('hit');

            try {
                return Image::make($cctv->full_url)->response();
            } catch (\Throwable $th) {
                return response()->json([
                    'status' => false,
                    'message' => 'Server CCTV sedang offline'
                ], 500);
            }
        }

        return response()->json([
            'status' => false,
            'message' => 'Server CCTV sedang offline'
        ], 500);
    }
}
