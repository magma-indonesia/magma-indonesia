<?php

namespace App\Http\Controllers;

use App\EventCatalog;
use App\EventType;
use App\Gadd;
use App\Http\Requests\EventCatalogRequest;
use Illuminate\Http\Request;
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
        return $request;
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
