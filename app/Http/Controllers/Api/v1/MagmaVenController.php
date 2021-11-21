<?php

namespace App\Http\Controllers\Api\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\v1\MagmaVen;
use App\Http\Requests\v1\Api\MagmaVenFilterRequest;
use App\Http\Resources\v1\MagmaVenCollection;
use App\Http\Resources\v1\MagmaVenResource;
use Illuminate\Support\Facades\Cache;

class MagmaVenController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $ven = MagmaVen::select('erupt_id')
            ->orderBy('erupt_tgl', 'desc')
            ->orderBy('erupt_jam', 'desc')
            ->first();

        $page = $request->has('page') ? $request->page : 1;

        $vens = Cache::remember('v1/api/letusan:' . $ven->erupt_id.':'.$page, 10, function () {
            return MagmaVen::with('user','gunungapi:ga_code,ga_zonearea,ga_nama_gapi,ga_lat_gapi,ga_lon_gapi,ga_elev_gapi')
                ->orderBy('erupt_tgl', 'desc')
                ->orderBy('erupt_jam', 'desc')
                ->paginate(15);
        });

        return new MagmaVenCollection($vens);
    }

    public function show($id)
    {
        $ven = Cache::remember('v1/api/letusan/' . $id, 60, function () use ($id) {
            return MagmaVen::where('erupt_id', $id)
            ->with('user', 'gunungapi:ga_code,ga_zonearea,ga_nama_gapi,ga_lat_gapi,ga_lon_gapi,ga_elev_gapi')
            ->firstOrFail();
        });

        return new MagmaVenResource($ven);
    }

    public function latest()
    {
        $ven = MagmaVen::with('user', 'gunungapi:ga_code,ga_zonearea,ga_nama_gapi,ga_lat_gapi,ga_lon_gapi,ga_elev_gapi')
                ->orderBy('erupt_tgl', 'desc')
                ->orderBy('erupt_jam', 'desc')
                ->first();

        return new MagmaVenResource($ven);
    }

    /**
     * Filter VEN
     *
     * @param \App\Http\Requests\v1\Api\MagmaVenFilterRequest $request
     * @return \Illuminate\Http\Response
     */
    public function filter(MagmaVenFilterRequest $request)
    {
        $validated = $request->validated();

        $vens = MagmaVen::query();

        $vens->when($request->has('start_date'), function ($query) use ($validated) {
            $query->whereBetween('erupt_tgl', [$validated['start_date'], $validated['end_date']]);
        });

        $vens = $vens->where('ga_code', $validated['code'])
            ->orderBy('erupt_tgl', 'desc')
            ->paginate(15);

        return new MagmaVenCollection($vens);
    }
}
