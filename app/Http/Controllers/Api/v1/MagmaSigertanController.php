<?php

namespace App\Http\Controllers\Api\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\v1\Api\MagmaSigertanFilterRequest;
use App\v1\GertanCrs as Crs;
use App\Http\Resources\v1\MagmaSigertanCollection;
use App\Http\Resources\v1\MagmaSigertanShowResource;
use Illuminate\Support\Facades\Cache;

class MagmaSigertanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $last = Crs::select('idx', 'crs_log')->orderBy('idx', 'desc')->first();
        $page = $request->has('page') ? $request->page : 1;

        $date = strtotime($last->crs_log);

        $sigertans = Cache::remember('api/v1/home/sigertan:' . $page . ':' . $date, 10, function () {
            return Crs::has('tanggapan')
                ->with('tanggapan')
                ->where('crs_sta', 'TERBIT')
                ->whereBetween('crs_lat', [-12, 3])
                ->whereBetween('crs_lon', [89, 149])
                ->orderBy('crs_log', 'desc')
                ->paginate(15);
        });


        return new MagmaSigertanCollection($sigertans);
    }

    public function latest()
    {
        $sigertan = Crs::has('tanggapan')
            ->with('tanggapan')
            ->where('crs_sta', 'TERBIT')
            ->whereBetween('crs_lat', [-12, 2])
            ->whereBetween('crs_lon', [89, 149])
            ->orderBy('crs_log', 'desc')
            ->first();

        return new MagmaSigertanShowResource($sigertan);
    }

    public function show($id)
    {
        $sigertan = Crs::with('tanggapan')
            ->where('crs_ids', $id)
            ->firstOrFail();

        return new MagmaSigertanShowResource($sigertan);
    }

    public function filter(MagmaSigertanFilterRequest $request)
    {
        $validated = $request->validated();

        $sigertans = Crs::has('tanggapan')
            ->with('tanggapan')
            ->where('crs_sta', 'TERBIT')
            ->whereBetween('crs_lat', [-12, 3])
            ->whereBetween('crs_lon', [89, 149])
            ->whereBetween('crs_dtm', [
                $validated['start_date'],
                $validated['end_date']
            ])
            ->orderBy('crs_log', 'desc')
            ->paginate(15);

        return new MagmaSigertanCollection($sigertans);
    }
}
