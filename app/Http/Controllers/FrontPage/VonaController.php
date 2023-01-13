<?php

namespace App\Http\Controllers\FrontPage;

use App\Gadd;
use App\Vona;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\VonaHomeService;
use App\Traits\VonaTrait;
use App\v1\MagmaVen;
use Illuminate\Support\Carbon;

class VonaController extends Controller
{
    use VonaTrait;

    /**
     * Set locale loanguange to English
     */
    public function __construct()
    {
        Carbon::setLocale('en');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, VonaHomeService $vonaHomeService)
    {
        return view('home.vona.index', [
            'gadds' => $vonaHomeService->gadds(),
            'vonas' => $vonaHomeService->indexVona($request)->get(),
            'grouped' => $vonaHomeService->grouped($request),
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Vona  $vona
     * @return \Illuminate\Http\Response
     */
    public function show(Vona $vona)
    {
        $vona = $vona->load('gunungapi');
        $ven = $vona->old_ven_uuid ?
            MagmaVen::where('uuid', $vona->old_ven_uuid)->first() :
            null;

        return view('home.vona.show', [
            'vona' => $vona,
            'location' => $this->location($vona),
            'volcano_activity_summary' => $this->volcanoActivitySummary($vona),
            'volcanic_cloud_height' => $this->volcanicCloudHeight($vona),
            'other_volcanic_cloud_information' => $this->otherVolcanicCloudInformation($vona),
            'remarks' => $this->remarks($vona),
            'ven' => $ven,
        ]);
    }
}
