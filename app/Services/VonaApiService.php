<?php

namespace App\Services;

use App\Traits\VonaTrait;
use App\Vona;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\URL;

/**
 * VONA API service to handle VONA API
 */
class VonaApiService
{
    use VonaTrait;

    /**
     * Create share url for vona
     *
     * @param Vona $vona
     * @return array
     */
    public function share(Vona $vona): array
    {
        return [
            'url' => URL::signedRoute('vona.show', $vona),
            'description' => "{$this->volcanoActivitySummary($vona)} {$this->volcanicCloudHeight($vona)}",
            'photo' => $vona->old_ven_uuid ? $vona->old_ven->erupt_pht : null,
        ];
    }

    /**
     * Create response for vona index
     *
     * @param Vona $vona
     * @return array
     */
    public function vonaIndexResponse(Vona $vona): array
    {
        return [
            'uuid' => $vona->uuid,
            'noticenumber' => $vona->noticenumber,
            'type' => $vona->type == 'REAL' ? 'Real' : 'Exercise',
            'volcano' => [
                'area' => "{$vona->gunungapi->province_en}, Indonesia",
                'code' => $vona->gunungapi->code,
                'name' => $vona->gunungapi->name,
                'number' => $vona->gunungapi->smithsonian_id,
                'latitude' => $vona->gunungapi->latitude,
                'longitude' => $vona->gunungapi->longitude,
                'elevation' => $vona->gunungapi->elevation,
            ],
            'datetime' => [
                'local' => $vona->issued,
                'utc' => $vona->issued_utc,
            ],
            'color_code' => [
                'current' => $vona->current_code,
                'previous' => $vona->previous_code,
            ],
            'ash_information' => [
                'height_in_meter' => $vona->ash_height,
                'height_in_ft' => $vona->ash_height * 3.2,
                'height_above_sea_level_in_meter' => $vona->gunungapi->elevation + $vona->ash_height,
                'height_above_sea_level_in_ft' => ($vona->gunungapi->elevation + $vona->ash_height) * 3.2,
                'intensity' => $this->translateAshIntensity($vona->ash_intensity),
                'color' => $this->translateAshColor($vona->ash_color),
                'directions' => $this->translateAshDirections($vona->ash_directions),
            ],
            'api_url' => route('api.vona.show', $vona),
            'share' => $this->share($vona),
        ];
    }

    /**
     * Get descriptive index for vona
     *
     * @param Vona $vona
     * @return array
     */
    public function vonaIndexDescriptiveResponse(Vona $vona): array
    {
        return [
            'uuid' => $vona->uuid,
            'issued' => $vona->issued_utc,
            'type' => $vona->type == 'REAL' ? 'Real' : 'Exercise',
            'volcano' => "{$vona->gunungapi->name} ({$vona->gunungapi->smithsonian_id})",
            'latitude' => $vona->gunungapi->latitude,
            'longitude' => $vona->gunungapi->longitude,
            'elevation' => $vona->gunungapi->elevation,
            'current_color_code' => $vona->current_code,
            'previous_color_code' => strtolower($vona->previous_code),
            'source' => "{$vona->gunungapi->name} Volcano Observatory",
            'noticenumber' => $vona->noticenumber,
            'volcano_location' => $this->location($vona),
            'area' => $vona->gunungapi->province_en,
            'summit_elevation' => $this->summitElevation($vona),
            'volcanic_activity_summary' => $this->volcanoActivitySummary($vona),
            'volcanic_cloud_height' => $this->volcanicCloudHeight($vona),
            'remarks' => $this->remarks($vona),
            'api_url' => route('api.vona.show.descriptive', $vona),
            'share' => $this->share($vona),
        ];
    }

    /**
     * Get VONAS
     *
     * @return LengthAwarePaginator
     */
    public function vonas(): LengthAwarePaginator
    {
        return Vona::with('gunungapi', 'old_ven')
            ->orderBy('issued', 'desc')
            ->where('is_sent', 1)
            ->paginate(30, ['*'], 'vona_page');
    }

    /**
     * Show vona in descriptive
     *
     * @param Vona $vona
     * @return array
     */
    public function showVonaDescriptive(Vona $vona): array
    {
        return $this->vonaIndexDescriptiveResponse($vona);
    }

    /**
     * Show vona resource
     *
     * @param Vona $vona
     * @return array
     */
    public function vonaShowResponse(Vona $vona): array
    {
        return array_merge($this->vonaIndexResponse($vona), [
            'remarks' => blank($this->remarks($vona)) ?: null,
        ]);
    }

    /**
     * Get vona descriptive
     *
     * @return LengthAwarePaginator
     */
    public function indexVonaDescriptive(): LengthAwarePaginator
    {
        $vonas = $this->vonas();
        $vonas->getCollection()->transform(function (Vona $vona) {
            return $this->vonaIndexDescriptiveResponse($vona);
        });

        return $vonas;
    }

    /**
     * Get vona index
     *
     * @param LengthAwarePaginator|null $vonas
     * @return LengthAwarePaginator
     */
    public function indexVona(?LengthAwarePaginator $vonas = null): LengthAwarePaginator
    {
        $vonas = $vonas ?: $this->vonas();
        $vonas->getCollection()->transform(function (Vona $vona) {
            return $this->vonaIndexResponse($vona);
        });

        return $vonas;
    }

    /**
     * Latest VONA
     *
     * @return array
     */
    public function latestVona(): array
    {
        $vona = Vona::orderByDesc('issued')->first();

        return $this->vonaShowResponse($vona);
    }

    /**
     * Latest VONA Descriptive
     *
     * @return array
     */
    public function latestVonaDescriptive(): array
    {
        $vona = Vona::orderByDesc('issued')->first();

        return $this->showVonaDescriptive($vona);
    }
}