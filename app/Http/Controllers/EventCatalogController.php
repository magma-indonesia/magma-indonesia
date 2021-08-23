<?php

namespace App\Http\Controllers;

use App\EventCatalog;
use App\EventType;
use App\Gadd;
use App\Http\Requests\EventCatalogRequest;
use App\Http\Requests\EventCatalogUpdate;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;

class EventCatalogController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     * @param  \Illuminate\Http\Request  $request
     */
    public function index(Request $request)
    {
        $eventCatalogs = EventCatalog::query();

        if (count($request->toArray())) {
            // buat diisi filter
        }

        return view('gunungapi.event-catalog.index', [
            'gadds' => Cache::remember('event-catalog/seismometer', 120, function () {
                return Gadd::has('seismometers')
                        ->select('code', 'name')
                        ->orderBy('name')
                        ->get();
            }),
            'types' => Cache::remember('event-types', 120, function() {
                return EventType::all();
            }),
            'eventCatalogs' => $eventCatalogs->with(
                'gunungapi:code,name',
                'user:nip,name',
                'type:code,name',
                'seismometer:scnl',
            )->orderBy('p_datetime_utc')
            ->paginate(100),
        ]);
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
                    'Y-m-d H:i:s.u',
                    $validated['p_times'][$key],
                    $validated['zones'][$key]
                )->setTimezone('UTC')->format('Y-m-d H:i:s.u'),
                's_datetime_utc' => $validated['s_times'][$key] == null ?
                null : Carbon::createFromFormat(
                    'Y-m-d H:i:s.u',
                    $validated['s_times'][$key],
                    $validated['zones'][$key]
                )->setTimezone('UTC')->format('Y-m-d H:i:s.u'),
                'p_datetime_local' => $validated['p_times'][$key],
                's_datetime_local' => $validated['s_times'][$key],
                'p_s_duration' => $this->valueBetweenPS($validated['p_times'][$key], $validated['s_times'][$key], $validated['zones'][$key]),
                'timezone' => $validated['zones'][$key],
                'duration' => $validated['durations'][$key],
                'maximum_amplitude' => $validated['amplitudes'][$key],
                'nip' => auth()->user()->nip,
                'created_at' => now(),
            ];
        }

        EventCatalog::insert($data);

        return redirect()->route('chambers.event-catalog.index')
                ->with('flash_event','Event berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\EventCatalog  $eventCatalog
     * @return \Illuminate\Http\Response
     */
    public function show(EventCatalog $eventCatalog)
    {
        return $eventCatalog;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\EventCatalog  $eventCatalog
     * @return \Illuminate\Http\Response
     */
    public function edit(EventCatalog $eventCatalog)
    {
        if (auth()->user()->nip != $eventCatalog->nip) {
            abort(403);
        }

        return view('gunungapi.event-catalog.edit', [
            'eventCatalog' => $eventCatalog->load('gunungapi:code,name', 'type:code,name', 'seismometer'),
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
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\EventCatalogUpdate  $request
     * @param  \App\EventCatalog  $eventCatalog
     * @return \Illuminate\Http\Response
     */
    public function update(EventCatalogUpdate $request, EventCatalog $eventCatalog)
    {
        $validated = $request->validated();

        $pTimeUTC = Carbon::createFromFormat(
            'Y-m-d H:i:s.u',
            $validated['p_time'],
            $validated['zone']
        )->setTimezone('UTC');

        $sTimeUTC = $validated['s_time'] == null ? null : Carbon::createFromFormat(
            'Y-m-d H:i:s.u',
            $validated['s_time'],
            $validated['zone']
        )->setTimezone('UTC');

        $eventCatalog->code = $validated['code'];
        $eventCatalog->scnl = $validated['seismometer_id'];
        $eventCatalog->code_event = $validated['event'];
        $eventCatalog->p_datetime_utc = $pTimeUTC->format('Y-m-d H:i:s.u');
        $eventCatalog->s_datetime_utc = $sTimeUTC == null ? null : $sTimeUTC->format('Y-m-d H:i:s.u');
        $eventCatalog->p_datetime_local = $validated['p_time'];
        $eventCatalog->s_datetime_local = $validated['s_time'];
        $eventCatalog->p_s_duration = $this->valueBetweenPS($validated['p_time'], $validated['s_time'], $validated['zone']);
        $eventCatalog->timezone = $validated['zone'];
        $eventCatalog->duration = $validated['duration'];
        $eventCatalog->maximum_amplitude = $validated['amplitude'];

        $eventCatalog->save();

        return redirect()->route('chambers.event-catalog.index')
                ->with('flash_event','Event berhasil dirubah.');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\EventCatalog  $eventCatalog
     * @return \Illuminate\Http\Response
     */
    public function destroy(EventCatalog $eventCatalog)
    {
        if (auth()->user()->nip != $eventCatalog->nip) {
            abort(403);
        }

        if ($eventCatalog->delete()) {
            $data = [
                'success' => 1,
                'message' => 'Data berhasil dihapus.'
            ];

            return response()->json($data);
        }

        $data = [
            'success' => 0,
            'message' => 'Gagal dihapus.'
        ];

        return response()->json($data);
    }

    /**
     * Get different time between P and S in milisecond
     *
     * @param Str $p_time
     * @param Str $s_time|null
     * @param Str $time_zone
     * @return Float|null
     *
     */
    protected function valueBetweenPS($p_time, $s_time = null, $time_zone)
    {
        if ($s_time == null) {
            return null;
        }

        $pTimeUTC = Carbon::createFromFormat(
            'Y-m-d H:i:s.u',
            $p_time,
            $time_zone
        )->setTimezone('UTC');

        $sTimeUTC = Carbon::createFromFormat(
            'Y-m-d H:i:s.u',
            $s_time,
            $time_zone
        )->setTimezone('UTC');

        return $sTimeUTC->diffInMilliseconds($pTimeUTC) / 1000;
    }
}
