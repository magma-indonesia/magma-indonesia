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
     * Get cache name for vona
     *
     * @param string $type
     * @param string $uuid
     * @param string|null $page
     * @param string|null $code
     * @param string|null $color
     * @return string
     */
    protected function cacheName(
        string $type,
        string $uuid,
        ?string $page = "1",
        ?string $code = null,
        ?string $color = null
    ): string
    {
        return "home/{$type}:{$uuid}-{$page}{$code}{$color}";
    }

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
     * @param LengthAwarePaginator|null $vonas
     * @return $this
     */
    public function grouped(?LengthAwarePaginator $vonas = null): self
    {
        $vonas = is_null($vonas) ? $this->get() : $vonas;

        $this->vonas = $vonas->groupBy(function ($vona) {
            return substr($vona->issued, 0, 10);
        });

        return $this;
    }

    /**
     * Get cache VONA
     *
     * @param Request $request
     * @return LengthAwarePaginator
     */
    public function indexVonaCache(Request $request): LengthAwarePaginator
    {
        $vonas = Vona::query();

        if ($request->has('code')) {
            $vonas->where('code_id', $request->code);
        }

        if ($request->has('color')) {
            $vonas->where('current_code', $request->color);
        }

        return $vonas->where('is_sent', 1)
            ->orderByDesc('issued')
            ->paginate(15);
    }

    /**
     * Get VONA's
     *
     * @param Request $request
     * @return $this
     */
    public function indexVona(Request $request): self
    {
        $vona = Vona::orderBy('issued', 'desc')->first();

        $cacheName = $this->cacheName(
            'vonas',
            $vona->uuid,
            $request->page,
            $request->code,
            $request->color
        );

        $vonas = Cache::tags(['fp-vona.index'])->remember(
            $cacheName, 30, function () use ($request) {
                return $this->indexVonaCache($request);
            }
        );

        if ($vonas->isEmpty()) {
            abort(404, 'No VONA found. Please check your search parameters.');
        }

        $this->vonas = $vonas;

        return $this;
    }

    /**
     * Get VONA's
     *
     * @return mixed
     */
    public function get()
    {
        return $this->vonas;
    }
}