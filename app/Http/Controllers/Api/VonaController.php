<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\VonaFilterRequest;
use App\Services\VonaApiService;
use App\Vona;

class VonaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(VonaApiService $vonaApiService)
    {
        $vonas = $vonaApiService->indexVona();

        return response()->json($vonas);
    }

    /**
     * Get vona index in descriptive
     *
     * @param VonaApiService $vonaApiService
     * @return void
     */
    public function indexDescriptive(VonaApiService $vonaApiService)
    {
        $vonas = $vonaApiService->indexVonaDescriptive();

        return response()->json($vonas);
    }

    /**
     * Show vona in descriptive
     *
     * @param string $uuid
     * @param VonaApiService $vonaApiService
     * @return void
     */
    public function showDescriptive(string $uuid, VonaApiService $vonaApiService)
    {
        $vona = Vona::findOrFail($uuid);

        return response()->json(
            $vonaApiService->showVonaDescriptive($vona)
        );
    }

    /**
     * Display the specified resource.
     *
     * @param string $uuid
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(string $uuid, VonaApiService $vonaApiService)
    {
        $vona = Vona::findOrFail($uuid);

        return response()->json(
            $vonaApiService->vonaShowResponse($vona)
        );
    }

    /**
     * Get latest vona
     *
     * @param VonaApiService $vonaApiService
     * @return \Illuminate\Http\JsonResponse
     */
    public function latest(VonaApiService $vonaApiService)
    {
        return response()->json($vonaApiService->latestVona());
    }

    /**
     * Undocumented function
     *
     * @param VonaApiService $vonaApiService
     * @return \Illuminate\Http\JsonResponse
     */
    public function latestDescriptive(VonaApiService $vonaApiService)
    {
        return response()->json($vonaApiService->latestVonaDescriptive());
    }

    /**
     * Filtering vonas
     *
     * @param VonaFilterRequest $request
     * @param VonaApiService $vonaApiService
     * @return \Illuminate\Http\JsonResponse
     */
    public function filter(VonaFilterRequest $request, VonaApiService $vonaApiService)
    {
        $vonas = Vona::query();

        if ($request->filled('code')) {
            $vonas->where('code_id', $request->code);
        }

        if ($request->filled('start_date')) {
            $vonas->where('issued','>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $vonas->where('issued', '<=', $request->end_date);
        }

        return $vonaApiService->indexVona($vonas->paginate());
    }
}
