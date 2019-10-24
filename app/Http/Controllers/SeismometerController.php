<?php

namespace App\Http\Controllers;

use App\Gadd;
use App\Seismometer;
use Illuminate\Http\Request;

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
}
