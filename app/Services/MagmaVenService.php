<?php

namespace App\Services;

use App\Gadd;
use App\VarRekomendasi;
use Illuminate\Database\Eloquent\Collection;

class MagmaVenService
{
    /**
     * Get volcanoes which has seismometer
     *
     * @param string $code
     * @return Gadd
     */
    public function gadds(string $code = 'AGU'): Collection
    {
        return Gadd::has('seismometers')
            ->with(['seismometers' => function ($query) use ($code) {
                $query->where('code', $code);
            }])->select('code', 'name')
            ->orderBy('name')
            ->get();
    }

    /**
     * Get recomendastion based on volcano code
     *
     * @param string $code
     * @return VarRekomendasi
     */
    public function recomendations(string $code = 'AGU'): Collection
    {
        return VarRekomendasi::select('id', 'code_id', 'rekomendasi')
            ->where('code_id', $code)
            ->where('status', 1)
            ->orderByDesc('created_at')
            ->get();
    }
}