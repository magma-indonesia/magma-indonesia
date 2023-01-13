<?php

namespace App\Http\Controllers\FrontPage;

use App\Gadd;
use App\Vona;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Traits\VonaTrait;
use App\v1\MagmaVen;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class VonaController extends Controller
{
    use VonaTrait;

    public function __construct()
    {
        Carbon::setLocale('en');
    }

    protected function cacheName()
    {

    }

    protected function grouped($vonas, $page)
    {
        return Cache::tags(['fp-vona.index'])->remember(
            'home/vona:grouped:'.$vonas->first()->uuid.':'.$page, 30,
            function() use($vonas) {
                return $vonas->groupBy(function ($vona) {
                    return substr($vona->issued, 0, 10);
                });
            }
        );
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $page = $request->has('page') ? $request->page : 1;

        $gadds = Gadd::whereHas('vonas', function ($query) {
                            $query->where('is_sent', 1);
                        })->orderBy('name')
                        ->withCount('vonas')
                        ->get();

        $vonas = Vona::query();

        if ($request->has('code')) {
            $vonas->where('code_id', $request->code);
        }

        $vonas = $vonas->where('is_sent', 1)
                    ->orderByDesc('issued')
                    ->paginate(15);

        $grouped = $this->grouped($vonas, $page);

        return view('home.vona.index', [
            'gadds' => $gadds,
            'vonas' => $vonas,
            'grouped' => $grouped,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Vona  $vona
     * @return \Illuminate\Http\Response
     */
    public function edit(Vona $vona)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Vona  $vona
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Vona $vona)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Vona  $vona
     * @return \Illuminate\Http\Response
     */
    public function destroy(Vona $vona)
    {
        //
    }
}
