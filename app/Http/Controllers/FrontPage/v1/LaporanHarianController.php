<?php

namespace App\Http\Controllers\FrontPage\v1;

use App\Http\Controllers\Controller;
use App\Jobs\UpdateLaporanHarianStatistik;
use App\Services\LaporanHarianService;

class LaporanHarianController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(LaporanHarianService $laporanHarianService, ?string $date = null)
    {
        UpdateLaporanHarianStatistik::dispatch();

        return view('v1.home.laporan-harian', [
            'date' => $laporanHarianService->date($date),
            'is_cached' => $laporanHarianService->isCached(),
            'groupedByStatus' => $laporanHarianService->groupedByStatus()
        ]);
    }
}