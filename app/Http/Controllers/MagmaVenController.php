<?php

namespace App\Http\Controllers;

use App\Http\Requests\MagmaVenStoreRequest;
use App\MagmaVen;
use App\Services\MagmaVenService;
use Illuminate\Http\Request;

class MagmaVenController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $magmaVens = MagmaVen::with('gunungapi:code,name')
            ->orderBy('datetime_utc', 'desc')->paginate(30);

        return view('gunungapi.magma-ven.index', [
            'magmaVens' => $magmaVens
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(MagmaVenService $magmaVenService)
    {
        return view('gunungapi.magma-ven.create', [
            'gadds' => $magmaVenService->gadds(),
            'recomendations' => $magmaVenService->recomendations(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(MagmaVenStoreRequest $request)
    {
        return $request;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\MagmaVen  $magmaVen
     * @return \Illuminate\Http\Response
     */
    public function show(MagmaVen $magmaVen)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\MagmaVen  $magmaVen
     * @return \Illuminate\Http\Response
     */
    public function edit(MagmaVen $magmaVen)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\MagmaVen  $magmaVen
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, MagmaVen $magmaVen)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\MagmaVen  $magmaVen
     * @return \Illuminate\Http\Response
     */
    public function destroy(MagmaVen $magmaVen)
    {
        //
    }
}
