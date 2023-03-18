<?php

namespace App\Http\Controllers\Api;

use App\Gadd;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class GaddController extends Controller
{
    /**
     * Get list of volcanoes
     *
     * @return Collection
     */
    public function index(): Collection
    {
        return Cache::rememberForever('api:gadd', function () {
            return Gadd::all();
        });
    }

    /**
     * Show volcano by code or smithsonian_id
     *
     * @param string $code
     * @return Gadd
     */
    public function show(string $code): Gadd
    {
        return Cache::rememberForever("api:gadd:$code", function () use ($code) {
            $gadd = Gadd::query();

            if (strlen($code) === 3) {
                return $gadd->where('code', $code)->firstOrFail();
            }

            return $gadd->where('smithsonian_id', $code)->firstOrFail();
        });
    }

    /**
     * Filter by codes or smithsonian_id(s)
     *
     * @param Request $request
     * @return Collection
     */
    public function filter(Request $request): Collection
    {
        $gadd = Gadd::query();

        if ($request->has('codes')) {
            $gadd->whereIn('code', explode(',', $request->codes));
        };

        if ($request->has('volcano_numbers')) {
            $gadd->whereIn('smithsonian_id', explode(',', $request->volcano_numbers));
        };

        return $gadd->get();
    }
}
