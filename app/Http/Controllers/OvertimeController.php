<?php

namespace App\Http\Controllers;

use App\Overtime;
use App\Services\OvertimeService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class OvertimeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(
        Request $request,
        OvertimeService $overtimeService,
        string $date = null,
        bool $flush = false)
    {
        return view('overtime.index',
            $overtimeService->indexResponse($request, $date, $flush));
    }

    /**
     * Display the specified resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function show(
        Request $request,
        OvertimeService $overtimeService,
        string $nip,
        string $date = null
    )
    {
        return view('overtime.show-nip',
            $overtimeService->showResponse($request, $nip, $date));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Overtime  $overtime
     * @return \Illuminate\Http\Response
     */
    public function edit(Overtime $overtime)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Overtime  $overtime
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Overtime $overtime)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Overtime  $overtime
     * @return \Illuminate\Http\Response
     */
    public function destroy(Overtime $overtime)
    {
        //
    }
}
