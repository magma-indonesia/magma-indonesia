<?php

namespace App\Http\Controllers;

use App\EventCatalog;
use App\EventType;
use App\Gadd;
use App\Http\Requests\EventCatalogRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;

class EventCatalogController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return EventCatalog::all();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('gunungapi.event-catalog.create', [
            'gadds' => Cache::remember('event-catalog/seismometer', 120, function () {
                        return Gadd::has('seismometers')
                                ->select('code', 'name')
                                ->orderBy('name')
                                ->get();
                        }),
            'types' => EventType::orderBy('name')->get(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\EventCatalogRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(EventCatalogRequest $request)
    {
        $validated = $request->validated();

        foreach ($validated['events'] as $key => $event) {
            $data[] = [
                'code' => $validated['code'],
                'scnl' => $validated['seismometer_id'][$key],
                'code_event' => $event,
                'p_datetime_utc' => Carbon::createFromFormat(
                    'Y-m-d H:i:s.v',
                    $validated['p_times'][$key],
                    $validated['zones'][$key]
                )->setTimezone('UTC'),
                's_datetime_utc' => Carbon::createFromFormat(
                    'Y-m-d H:i:s.v',
                    $validated['s_times'][$key],
                    $validated['zones'][$key]
                )->setTimezone('UTC'),
                'p_datetime_local' => $validated['p_times'][$key],
                's_datetime_local' => $validated['s_times'][$key],
                'timezone' => $validated['zones'][$key],
                'duration' => $validated['durations'][$key],
                'maximum_amplitude' => $validated['durations'][$key],
                'nip' => auth()->user()->nip,
            ];
        }

        // return $data;

        EventCatalog::insert($data);

        return $data;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\EventCatalog  $eventCatalog
     * @return \Illuminate\Http\Response
     */
    public function show(EventCatalog $eventCatalog)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\EventCatalog  $eventCatalog
     * @return \Illuminate\Http\Response
     */
    public function edit(EventCatalog $eventCatalog)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\EventCatalog  $eventCatalog
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, EventCatalog $eventCatalog)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\EventCatalog  $eventCatalog
     * @return \Illuminate\Http\Response
     */
    public function destroy(EventCatalog $eventCatalog)
    {
        //
    }
}
