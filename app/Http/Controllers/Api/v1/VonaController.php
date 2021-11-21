<?php

namespace App\Http\Controllers\Api\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\v1\Api\VonaFilterRequest;
use App\v1\Vona;
use App\Http\Resources\v1\VonaResource;
use App\Http\Resources\v1\VonaCollection;
use Illuminate\Support\Facades\Cache;

class VonaController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $last_vona = Vona::select('no')->orderBy('no','desc')->first();
        $page = $request->has('vona_page') ? $request->vona_page : 1;

        $vonas = Cache::remember('v1/api/vonas-'.$last_vona->no.'-page-'.$page, 30, function() {
            return Vona::orderBy('no','desc')
                ->with('volcano:ga_code,ga_nama_gapi,ga_lat_gapi,ga_lon_gapi,ga_elev_gapi')
                ->where('sent',1)
                ->paginate(30,['*'],'vona_page');
        });

        return new VonaCollection($vonas);
    }

    public function latest()
    {
        $vona = Vona::with('volcano:ga_code,ga_nama_gapi,ga_lat_gapi,ga_lon_gapi,ga_elev_gapi')
                ->where('sent',1)->orderBy('no','desc')->first();

        return new VonaResource($vona);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Vona  $vona
     * @return \Illuminate\Http\Response
     */
    public function show($uuid)
    {
        $vona = Vona::with('volcano:ga_code,ga_nama_gapi,ga_lat_gapi,ga_lon_gapi,ga_elev_gapi')->findOrFail($uuid);
        return new VonaResource($vona);
    }

    /**
     * Filter Vona
     *
     * @param \App\Http\Requests\v1\Api\VonaFilterRequest $request
     * @return \Illuminate\Http\Response
     */
    public function filter(VonaFilterRequest $request)
    {
        $validated = $request->validated();

        $vonas = Vona::query();

        $vonas->when($request->has('start_date'), function ($query) use ($validated) {
            $query->whereBetween('issued_time', [$validated['start_date'], $validated['end_date']]);
        });

        $vonas = $vonas->where('ga_code', $validated['code'])
            ->orderBy('issued_time', 'desc')
            ->paginate(15);

        return new VonaCollection($vonas);
    }

}
