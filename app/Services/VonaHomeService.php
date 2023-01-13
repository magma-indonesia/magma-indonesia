<?php

namespace App\Services;

use App\Gadd;
use App\Vona;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class VonaHomeService
{
    public $vonas;

    public $vona;

    /**
     * Gadd's collection
     *
     * @return Collection
     */
    public function gadds(): Collection
    {
        return Gadd::whereHas('vonas', function ($query) {
                $query->where('is_sent', 1);
            })->orderBy('name')
            ->withCount('vonas')
            ->get();
    }

    /**
     * Gruped VONA by date
     *
     * @param Request $request
     * @param LengthAwarePaginator|null $vonas
     * @return Collection
     */
    public function grouped(Request $request, ?LengthAwarePaginator $vonas = null): Collection
    {
        $page = $request->has('page') ? $request->page : '1';
        $vonas = is_null($vonas) ? $this->get() : $vonas;

        return Cache::tags(['fp-vona.index'])->remember(
            'home/vona:grouped:' . $vonas->first()->uuid . ':' . $page, 30,
            function () use ($vonas) {
                return $vonas->groupBy(function ($vona) {
                    return substr($vona->issued, 0, 10);
                });
            }
        );
    }

    /**
     * Get VONA's
     *
     * @param Request $request
     * @return $this
     */
    public function indexVona(Request $request): self
    {
        $vonas = Vona::query();

        if ($request->has('code')) {
            $vonas->where('code_id', $request->code);
        }

        if ($request->has('color')) {
            $vonas->where('current_code', $request->color);
        }

        $vonas = $vonas->where('is_sent', 1)
            ->orderByDesc('issued')
            ->paginate(15);

        if ($vonas->isEmpty()) {
            abort(404, 'No VONA found. Please check your search parameters.');
        }

        $this->vonas = $vonas;

        return $this;
    }

    /**
     * Get VONA's
     *
     * @return LengthAwarePaginator
     */
    public function get(): LengthAwarePaginator
    {
        return $this->vonas;
    }
}