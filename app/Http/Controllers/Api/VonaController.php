<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Vona;
use App\Http\Resources\VonaResource;
use App\Http\Resources\VonaCollection;
use App\Traits\VonaLocation;

class VonaController extends Controller
{

    use VonaLocation;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $vonas = Vona::orderBy('issued','desc')
                    ->where('is_sent',1)
                    ->paginate(30,['*'],'vona_page');

        $vonas->getCollection()->transform(function ($vona) {
            return $vona->issued;
        });

        return response()->json($vonas);

        return $vonas;
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
