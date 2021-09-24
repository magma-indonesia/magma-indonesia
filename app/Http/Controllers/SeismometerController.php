<?php

namespace App\Http\Controllers;

use App\Gadd;
use App\Seismometer;
use App\LiveSeismogram;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;

class SeismometerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $gadds = Gadd::has('seismometers')
                    ->select('code','name')
                    ->with('seismometers')
                    ->withCount('seismometers')
                    ->orderBy('name')
                    ->get();

        $lives = Seismometer::with('live_seismogram','gunungapi:code,name')
                    ->wherePublished(1)
                    ->get();

        $lives->each(function ($live) {

            $live_seismogram = $live->live_seismogram;

            try {
                $path = Storage::disk('seismogram')->get('thumb_' . $live_seismogram->filename);
                $image = Image::make($path)->stream('data-url');

                $live_seismogram['image'] = $image;
            } catch (\Throwable $th) {
                $live_seismogram['image'] = null;

            }
        });

        return view('gunungapi.seismometer.index', compact('gadds','lives'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $gadds = Gadd::all();
        return view('gunungapi.seismometer.create', compact('gadds'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        Seismometer::firstOrCreate(
            [
                'scnl' => $request->station.'_'.$request->channel.'_'.$request->network.'_'.$request->location,
            ],
            [
                'code' => $request->code,
                'lokasi' => $request->lokasi,
                'station' => $request->station,
                'channel' => $request->channel,
                'network' => $request->network,
                'location' => $request->location,
                'elevation' => $request->elevation,
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
                'elevation' => $request->elevation,
                'is_active' => $request->is_active,
                'published' => $request->published,
            ]
        );

        Artisan::call('update:live_seismogram');

        // Used in EventCatalogController@create
        Cache::forget('event-catalog/seismometer');

        return redirect()->route('chambers.seismometer.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Seismometer  $seismometer
     * @return \Illuminate\Http\Response
     */
    public function show(Seismometer $seismometer)
    {
        $gadds = Gadd::all();
        return view('gunungapi.seismometer.create', compact('gadds'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Seismometer  $seismometer
     * @return \Illuminate\Http\Response
     */
    public function edit(Seismometer $seismometer)
    {
        $gadds = Gadd::all();
        return view('gunungapi.seismometer.edit', compact('gadds','seismometer'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Seismometer  $seismometer
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Seismometer $seismometer)
    {
        $seismometer = Seismometer::findOrFail($seismometer->id);

        Seismometer::updateOrCreate(
            [
                'scnl' => $request->station.'_'.$request->channel.'_'.$request->network.'_'.$request->location,
            ],
            [
                'code' => $request->code,
                'lokasi' => $request->lokasi,
                'station' => $request->station,
                'channel' => $request->channel,
                'network' => $request->network,
                'location' => $request->location,
                'elevation' => $request->elevation,
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
                'elevation' => $request->elevation,
                'is_active' => $request->is_active,
                'published' => $request->published,
            ]
        );

        if ($request->is_active)
        {
            LiveSeismogram::updateOrCreate(            [
                'seismometer_id' => $seismometer->id
            ],[
                'code' => $request->code,
                'filename' => null,
            ]);
        }

        Artisan::call('update:live_seismogram');

        return redirect()->route('chambers.seismometer.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Seismometer  $seismometer
     * @return \Illuminate\Http\Response
     */
    public function destroy(Seismometer $seismometer)
    {
        if ($seismometer->delete())
        {
            return response()->json([
                'success' => 1,
                'message' => 'Berhasil dihapus'
            ]);
        }

        return response()->json([
            'success' => 0,
            'message' => 'Gagal dihapus'
        ]);
    }

    public function partial($code, $id)
    {
        $gadds = Gadd::has('seismometers')
                        ->with('seismometers')
                        ->whereCode($code)
                        ->select('code', 'name')
                        ->orderBy('name')
                        ->get();

        return view('gunungapi.letusan.partial-seismometer', compact('gadds','id'));
    }

    public function partialScnl($code, $scnl)
    {
        $gadds = Gadd::has('seismometers')
            ->with('seismometers')
            ->whereCode($code)
            ->select('code', 'name')
            ->orderBy('name')
            ->get();

        return view('gunungapi.event-catalog.partial-seismometer', compact('gadds', 'scnl'));
    }
}
