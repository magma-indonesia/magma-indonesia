<?php

namespace App\Http\Controllers;

use App\Overtime;
use App\Services\OvertimeService;
use Illuminate\Http\Request;

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
        $overtimeService->flush($flush)->date($date)->pengamatOnly($request);

        $datesPeriod = $overtimeService->datesPeriod();

        return view('overtime.index', [
            'date' => $overtimeService->date,
            'pengamat_only' => $overtimeService->pengamatOnly,
            'selected_date' => $overtimeService->date->formatLocalized('%B %Y'),
            'is_cached' => $overtimeService->isCachedForever(),
            'dates_period' => $overtimeService->dates,
            'disable_order' => $overtimeService->disableOrder(),
            'colspan' => $datesPeriod->count(),
            'overtimes' => $overtimeService->cacheIndex()->values(),
        ]);
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
        $overtimeService->date($date)->nip($request, $nip)->flush(true);

        return [
            'cached' => [
                'is_cached' => $overtimeService->isCachedForever(),
                'cached_name' => $overtimeService->cacheShowName()
            ],
            'date' => $overtimeService->date,
            'user' => $overtimeService->user,
            'reports' => collect($overtimeService->reports)->transform(function ($report, $key) {
                switch ($key) {
                    case 'vars':
                        return 'Volcanic Activity Report (VAR)';
                    case 'vens':
                        return 'Volcanic Eruption Notice (VEN)';
                    default:
                        return 'Volcano Observatory Notice for Aviation (VONA)';
                }
            }),
            'selected_date' => $overtimeService->date,
            'overtimes' => $overtimeService->cacheShow()->first(),
        ];

        return view('overtime.show-nip', [
            'cached' => [
                'is_cached' => $overtimeService->isCachedForever(),
                'cached_name' => $overtimeService->cacheShowName()
            ],
            'date' => $overtimeService->date,
            'user' => $overtimeService->user,
            'reports' => collect($overtimeService->reports)->transform(function ($report, $key) {
                switch ($key) {
                    case 'vars':
                        return 'Volcanic Activity Report (VAR)';
                    case 'vens':
                        return 'Volcanic Eruption Notice (VEN)';
                    default:
                        return 'Volcano Observatory Notice for Aviation (VONA)';
                }
            }),
            'selected_date' => $overtimeService->date,
            'overtimes' => $overtimeService->cacheShow()->first(),
        ]);
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
