<?php

namespace App\Http\Controllers\Api\v1;

use App\Gadd;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\KameraGunungApi;
use Karlmonson\Ping\Facades\Ping;
use Intervention\Image\Facades\Image;

class KameraGunungApiController extends Controller
{
    protected function filteredCCTV($code)
    {
        return KameraGunungApi::with('gunungapi:code,name')
            ->where('publish', 1)
            ->where('code', $code)
            ->orderBy('code')
            ->get()
            ->makeHidden(['full_url']);
    }

    protected function nonFilteredCCTV()
    {
        return KameraGunungApi::with('gunungapi:code,name')
            ->where('publish', 1)
            ->orderBy('code')
            ->get()
            ->makeHidden(['full_url']);
    }

    public function index()
    {
        $health = Ping::check(config('app.cctv_url'));

        if ($health == 200) {
            $gadds = Gadd::select('code', 'name')
                ->whereHas('cctv', function ($query) {
                    $query->where('publish', 1);
                })
                ->withCount('cctv')
                ->get();

            $cctvs = $this->nonFilteredCCTV();

            if ($cctvs->isEmpty())
                abort(404);

            $cctvs->each(function ($item, $key) {
                try {
                    $image = Image::make($item->full_url)
                        ->widen(150)->stream('data-url');
                    $item['image'] = $image;
                } catch (\Exception $e) {
                    $item['image'] = null;
                }
            });

            return [
                'cctvs' => $cctvs,
                'gadds' => $gadds
            ];
        }

        return response()->json([
            'message' => 'Server CCTV sedang offline'
        ], 500);
    }

    public function filter($code)
    {
        $health = Ping::check(config('app.cctv_url'));

        if ($health == 200) {
            $gadds = Gadd::select('code', 'name')
                ->whereHas('cctv', function ($query) {
                    $query->where('publish', 1);
                })
                ->withCount('cctv')
                ->get();

            $cctvs = $this->filteredCCTV($code);

            if ($cctvs->isEmpty())
                abort(404);

            $cctvs->each(function ($item, $key) {
                try {
                    $image = Image::make($item->full_url)
                        ->widen(150)->stream('data-url');
                    $item['image'] = $image;
                } catch (\Exception $e) {
                    $item['image'] = null;
                }
            });

            return [
                'cctvs' => $cctvs,
            ];
        }

        return response()->json([
            'message' => 'Server CCTV sedang offline'
        ], 500);
    }

    public function show(Request $request)
    {
        $health = Ping::check(config('app.cctv_url'));

        if ($health == 200) {
            $cctv = KameraGunungApi::with('gunungapi:code,name')
                ->where('publish', 1)
                ->where('uuid', $request->uuid)
                ->firstOrFail();

            $cctv->increment('hit');

            try {
                return Image::make($cctv->full_url)->response();
            } catch (\Throwable $th) {
                return response()->json([
                    'message' => 'Server CCTV sedang offline'
                ], 500);
            }
        }

        return response()->json([
            'message' => 'Server CCTV sedang offline'
        ], 500);
    }
}
