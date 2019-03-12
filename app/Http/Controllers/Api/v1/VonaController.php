<?php

namespace App\Http\Controllers\Api\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
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
                ->where('sent',1)
                ->paginate(30,['*'],'vona_page');
        });

        return new VonaCollection($vonas);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Vona  $vona
     * @return \Illuminate\Http\Response
     */
    public function show($uuid)
    {
        $vona = Vona::findOrFail($uuid);
        return new VonaResource($vona);
    }

}
