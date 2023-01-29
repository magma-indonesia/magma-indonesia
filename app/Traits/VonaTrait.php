<?php

namespace App\Traits;

use App\v1\Gadd;
use App\Vona;
use App\v1\Vona as VonaOld;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;

trait VonaTrait
{

    protected $feet = 3.2;

    /**
     * Get Noticenumber of VONA
     *
     * @param Request $request
     * @return String
     */
    public function noticenumber(Request $request): string
    {
        $prefix = $request->type == 'real' ? '' : 'EXERCISE-';
        $year = now()->format('Y');

        $vonaCount = Vona::where('issued', 'like', "$year%")
            ->where('code_id', $request->code)
            ->where('type', $request->type)
            ->count();

        $vonaCount = sprintf('%03d', $vonaCount + 1);
        return $prefix . $year . $request->code . $vonaCount;
    }

    /**
     * Get date time in UTC
     *
     * @param string $datetime
     * @param string $tz
     * @return Carbon
     */
    protected function datetimeUtc(string $datetime, string $tz): Carbon
    {
        $datetime_utc = Carbon::createFromTimeString($datetime, $tz)->setTimezone('UTC');

        return $datetime_utc;
    }

    /**
     * Undocumented function
     *
     * @param string $zone
     * @return string
     */
    protected function zoneArea(string $zone): string
    {
        switch ($zone) {
            case 'WIB':
                return 'Asia/Jakarta';
            case 'WITA':
                return 'Asia/Makassar';
            default:
                return 'Asia/Jayapura';
        }
    }

    /**
     * Issued date (UTC) in VONA format
     *
     * @param Request $request
     * @return string
     */
    protected function issued(Request $request): string
    {
        $gadd = Gadd::select('ga_code', 'ga_nama_gapi', 'ga_id_smithsonian', 'ga_elev_gapi', 'ga_lon_gapi', 'ga_lat_gapi', 'ga_prov_gapi', 'ga_prov_gapi_en', 'ga_zonearea')->where('ga_code', $request->code)->first();

        $tz = $this->zoneArea($gadd->ga_zonearea);

        return $this->datetimeUtc($request->date, $tz)->format('Y-m-d H:i:s');
    }

    /**
     * Get preivous code
     *
     * @return string
     */
    public function previousCode(Request $request): string
    {
        $year = now()->format('Y');

        $latestVona = Vona::where('code_id', $request->code)
            ->where('issued', 'like', "$year%")
            ->where('type', $request->type)
            ->where('is_sent', 1)
            ->orderBy('issued', 'desc')
            ->first();

        if (is_null($latestVona))
            return 'unassigned';

        return $latestVona->current_code;
    }

    /**
     * Get ash volcano height above sea level
     *
     * @param Request $request
     * @return float
     */
    protected function ashCloudHeightAboveSeaLevel(Request $request): float
    {
        $elevation = Gadd::select('ga_code', 'ga_elev_gapi')
            ->where('ga_code', $request->code)->first()->ga_elev_gapi;

        return $request->height + $elevation;
    }

    /**
     * Get vona color code
     *
     * @param Request $request
     * @return string
     */
    protected function getColor(Request $request): string
    {
        $ashHeightAboveSeaLevel = $this->ashCloudHeightAboveSeaLevel($request);

        if ($request->erupsi_berlangsung) {
            if ($ashHeightAboveSeaLevel >= 6000)
                return 'RED';

            return 'ORANGE';
        }

        if ($request->visibility == 0) {
            if (($request->amplitudo == 0) and ($request->amplitudo_tremor == 0)) {
                return 'GREEN';
            }

            if (($request->amplitudo > 0)) {
                return 'ORANGE';
            }

            if (($request->amplitudo_tremor > 0)) {
                return 'ORANGE';
            }
        }

        if ($ashHeightAboveSeaLevel >= 6000)
            return 'RED';

        if ($ashHeightAboveSeaLevel > 0)
            return 'ORANGE';

        return 'YELLOW';
    }

    /**
     * Undocumented function
     *
     * @param Vona $vona
     * @return string
     */
    public function colorCss(string $currentColorCode): string
    {
        switch (strtoupper($currentColorCode)) {
            case 'GREEN':
                return 'bg-success';
            case 'YELLOW':
                return 'bg-yellow';
            case 'ORANGE':
                return 'bg-warning';
            case 'RED':
                return 'bg-danger';
            default:
                return 'bg-danger';
        }
    }

    /**
     * Get current color code if the choice is not Auto
     *
     * @param Request $request
     * @return string
     */
    protected function getColorNonAuto(Request $request): string
    {
        $ashHeightAboveSeaLevel = $this->ashCloudHeightAboveSeaLevel($request);

        if ($ashHeightAboveSeaLevel >= 6000)
            return 'RED';

        return strtoupper($request->color);
    }

    /**
     * Get vona current coior code
     *
     * @param Request $request
     * @return string
     */
    public function currentCode(Request $request): string
    {
        return $request->color === 'auto' ?
                $this->getColor($request) : $this->getColorNonAuto($request);
    }

    /**
     * Convert coordinate to VONA format
     *
     * @param [type] $coordinate
     * @param string $type
     * @return string
     */
    protected function coordinateToString($coordinate, string $type): string
    {
        [$degree, $decimal] = explode('.', $coordinate);

        $symbol = $degree > 0 ? 'N' : 'S';
        if ($type === 'longitude') {
            $symbol = 'E';
        }

        $decimal = abs($coordinate) - abs($degree);
        $minute = floor($decimal * 60);
        $second = round(($decimal * 3600) - ($minute * 60));

        $degree = $degree == '0' ? '0' : sprintf('%02s', abs($degree));
        $minute = sprintf('%02s', abs($minute));
        $second = sprintf('%02s', abs($second));

        return "$symbol $degree deg $minute min $second sec";
    }

    /**
     * Convert latitude to VONA format
     *
     * @param float $latitude
     * @return string
     */
    protected function convertLatitude(float $latitude): string
    {
        return $this->coordinateToString($latitude, 'latitude');
    }

    /**
     * Convert longitude to VONA format
     *
     * @param float $longitude
     * @return string
     */
    protected function convertLongitude(float $longitude): string
    {
        return $this->coordinateToString($longitude, 'longitude');
    }

    /**
     * Get latitude and loongitude in VONA format
     *
     * @param Vona $vona
     * @return string
     */
    protected function location(Vona $vona): string
    {
        $vona->load('gunungapi');
        return "{$this->convertLatitude($vona->gunungapi->latitude)} {$this->convertLongitude($vona->gunungapi->longitude)}";
    }

    /**
     * Volcano submit elevation
     *
     * @param Vona $vona
     * @return string
     */
    protected function summitElevation(Vona $vona): string
    {
        $feet = round($vona->gunungapi->elevation * $this->feet);
        return "{$feet} FT ({$vona->gunungapi->elevation}) M";
    }

    /**
     * Next notice
     *
     * @return void
     */
    public function nextNotice()
    {
        $route = URL::route('chambers.vona.index');
        $url = config('app.url');
        return 'A new VONA will be issued if conditions change significantly or the colour code is changes.<br>Latest Volcanic information is posted at <b>VONA | MAGMA Indonesia</b> Website.<br>Link: <a href="' . $route . '">' . $url . '/vona</a></td>"';
    }

    /**
     * Store VONA to VONA v1
     *
     * @param Request $request
     * @param Vona $vona
     * @return VonaOld
     */
    protected function storeToOldVona(Request $request, Vona $vona): VonaOld
    {
        $vona->load('user', 'gunungapi');

        $vonaOld  = VonaOld::create([
            'issued' => $vona->issued_utc,
            'issued_time' => $vona->issued,
            'type' => $vona->type,
            'ga_nama_gapi' => $vona->gunungapi->name,
            'ga_id_smithsonian' => $vona->gunungapi->smithsonian_id,
            'ga_code' => $vona->gunungapi->code,
            'cu_avcode' => $vona->current_code,
            'pre_avcode' => $vona->previous_code,
            'source' => "{$vona->gunungapi->name} Volcano Observatory",
            'notice_number' => $vona->noticenumber,
            'volcano_location' => $this->location($vona),
            'area' => "{$vona->gunungapi->province_en}, Indonesia",
            'summit_elevation' => $this->summitElevation($vona),
            'volcanic_act_summ' => $this->volcanoActivitySummary($vona),
            'vc_height' => $vona->ash_height + $vona->gunungapi->elevation,
            'vc_height_text' => $this->volcanicCloudHeight($vona),
            'other_vc_info' => $this->otherVolcanicCloudInformation($vona),
            'remarks' => blank($this->remarks($vona)) ? '-' : $this->remarks($vona),
            'contacts' => $vona::CONTACTS,
            'next_notice' => $this->nextNotice(),
            'sent' => 0,
            'nip' => $vona->user->nip,
            'nama' => $vona->user->name,
            'sender' => $vona->user->nip,
        ]);

        return $vonaOld;
    }

    protected function updateToOldVona(Vona $vona)
    {
        $vonaOld = VonaOld::findOrFail($vona->old_id);

        $vona->load('gunungapi');

        $vonaOld->update(['issued' => $vona->issued_utc,
            'issued_time' => $vona->issued,
            'type' => $vona->type,
            'ga_nama_gapi' => $vona->gunungapi->name,
            'ga_id_smithsonian' => $vona->gunungapi->smithsonian_id,
            'ga_code' => $vona->gunungapi->code,
            'cu_avcode' => $vona->current_code,
            'pre_avcode' => $vona->previous_code,
            'source' => "{$vona->gunungapi->name} Volcano Observatory",
            'volcano_location' => $this->location($vona),
            'area' => "{$vona->gunungapi->province_en}, Indonesia",
            'summit_elevation' => $this->summitElevation($vona),
            'volcanic_act_summ' => $this->volcanoActivitySummary($vona),
            'vc_height' => $vona->ash_height + $vona->gunungapi->elevation,
            'vc_height_text' => $this->volcanicCloudHeight($vona),
            'other_vc_info' => $this->otherVolcanicCloudInformation($vona),
            'remarks' => blank($this->remarks($vona)) ? '-' : $this->remarks($vona),
            'contacts' => $vona::CONTACTS,
            'next_notice' => $this->nextNotice(),
            'sent' => 1,
            'nip' => $vona->user->nip,
            'nama' => $vona->user->name,
            'sender' => $vona->user->nip,
        ]);

        return $vonaOld;
    }

    /**
     * Get text for ash cloud height in feet and meter
     *
     * @param Vona $vona
     * @return string
     */
    public function ashCloudHeight(Vona $vona): string
    {
        $ashCloudAboveSeaLevel = $vona->ash_height + $vona->gunungapi->elevation;

        $feet = round($ashCloudAboveSeaLevel * $this->feet);
        return "{$feet} FT ({$ashCloudAboveSeaLevel} M)";
    }

    /**
     * Get text for Volcanic Cloud Height
     *
     * @param Vona $vona
     * @return string
     */
    public function volcanicCloudHeight(Vona $vona): string
    {
        $feet = $vona->ash_height * $this->feet;

        $deskripsi = $vona->ash_height == 0 ?
            "Ash-cloud is not observed." :
            "Best estimate of ash-cloud top is around {$this->ashCloudHeight($vona)} above sea level or {$feet} FT ({$vona->ash_height} M) above summit. May be higher than what can be observed clearly. Source of height data: ground observer.";

        return $deskripsi;
    }

    /**
     * Get Volcanic Activity Summary
     *
     * @param Vona $vona
     * @return string
     */
    public function volcanoActivitySummary(Vona $vona): string
    {
        $utc = $vona->issued->format('Hi');
        $tz = $this->zoneArea($vona->gunungapi->zonearea);
        $local = Carbon::createFromTimeString($vona->issued, 'UTC')->setTimezone($tz)->format('Hi');

        if ($vona->is_visible) {
            return "Eruption with volcanic ash cloud at {$utc} UTC ({$local} local).";
        }

        if ($vona->amplitude > 0 || $vona->amplitude_tremor > 0) {
            return "Eruption at {$utc} UTC ({$local} local).";
        }

        return "Ash-cloud is not observed.";
    }

    /**
     * Ash intensity
     *
     * @param Vona $vona
     * @return string
     */
    public function ashIntensity(Vona $vona): string
    {
        if (count($vona->ash_intensity) == 1) {
            $intensity = strtolower(__($vona->ash_intensity[0]));
            return "The intensity of volcanic ash is observed to be {$intensity}.";
        }

        $intensity = $this->translateAshIntensity($vona->ash_intensity)
                ->transform(function ($intensity) {
                    return strtolower($intensity);
                });

        return "The intensity of volcanic ash is observed from {$intensity->first()} to {$intensity->last()}.";
    }

    /**
     * Translate Ash Intensity to english
     *
     * @param array $ashIntensity
     * @return Collection`
     */
    public function translateAshIntensity(array $ashIntensity): Collection
    {
        return collect($ashIntensity)->transform(function ($intensity) {
            return (__($intensity));
        });
    }

    /**
     * Ash color
     *
     * @param Vona $vona
     * @return string
     */
    public function ashColor(Vona $vona): string
    {
        if (count($vona->ash_color) == 1) {
            $color = strtolower(__($vona->ash_color[0]));
            return "Volcanic ash is observed to be {$color}.";
        }

        $colors = $this->translateAshColor($vona->ash_color)
            ->transform(function ($color) {
                return strtolower($color);
            });

        return "Volcanic ash is observed to be {$colors->first()} to {$colors->last()}.";
    }

    /**
     * UTranslate ash color to english
     *
     * @param array $ashColors
     * @return Collection
     */
    public function translateAshColor(array $ashColors): Collection
    {
        return collect($ashColors)->transform(function ($color) {
            return (__($color));
        });
    }

    /**
     * Ash direction.
     *
     * @param Vona $vona
     * @return string
     */
    public function ashDirections(Vona $vona): string
    {
        if (count($vona->ash_directions) == 1) {
            $direction = strtolower(__($vona->ash_directions[0]));
            return "Ash cloud moving to {$direction}.";
        }

        $directions = $this->translateAshDirections($vona->ash_directions)
            ->transform(function ($direction) {
                return strtolower($direction);
            })->toArray();

        $directions = Str::replaceLast(', ', ' to ', implode(', ', $directions));

        return "Ash cloud moving from {$directions}.";
    }

    /**
     * Undocumented function
     *
     * @param array $ashDirections
     * @return Collection
     */
    public function translateAshDirections(array $ashDirections): Collection
    {
        return collect($ashDirections)->transform(function ($direction) {
            return (__($direction));
        });
    }

    /**
     * Other Volcanic Cloud Information
     *
     * @param Vona $vona
     * @return string
     */
    public function otherVolcanicCloudInformation(Vona $vona): string
    {
        if ($vona->ash_height == 0) {
            return "Ash-cloud is not observed.";
        };

        $data = [
            $this->ashDirections($vona),
            $this->ashColor($vona),
            $this->ashIntensity($vona),
        ];

        return implode(' ', $data);
    }

    /**
     * Get text if eruption is continuing
     *
     * @param Vona $vona
     * @return string
     */
    public function eruptionIsContinuing(Vona $vona): string
    {
        return $vona->is_continuing ? 'Eruption and ash emission is continuing.' : '';
    }

    /**
     * Get text for tremor
     *
     * @param Vona $vona
     * @return string
     */
    public function eruptionTremor(Vona $vona): string
    {
        return $vona->amplitude_tremor > 0 ?
            "Tremor recorded on seismogram with maximum amplitude {$vona->amplitude_tremor} mm." : "";
    }

    /**
     * Get eruption seismogram recording
     *
     * @param Vona $vona
     * @return string
     */
    public function eruptionRecording(Vona $vona): string
    {
        if ($vona->amplitude == 0) {
            return "";
        }

        if ($vona->amplitude > 0 AND $vona->duration > 0) {
            return "Eruption recorded on seismogram with maximum amplitude {$vona->amplitude} mm and maximum duration {$vona->duration} second.";
        }

        return "Eruption recorded on seismogram with maximum amplitude {$vona->amplitude} mm.";
    }

    /**
     * Get remarks
     *
     * @param Vona $vona
     * @return string
     */
    public function remarks(Vona $vona): string
    {
        $data = array_filter([
            $this->eruptionIsContinuing($vona),
            $this->eruptionRecording($vona),
            $this->eruptionTremor($vona),
            strlen($vona->remarks) <= 5 ? '' : $vona->remarks,
        ]);

        return implode(' ', $data);
    }

    /**
     * Get duration based on condition
     *
     * @param Request $request
     * @return float
     */
    public function duration(Request $request): float
    {
        if ($request->erupsi_berlangsung || $request->color == 'green') {
            return 0;
        }

        return $request->durasi ?? 0;
    }

    /**
     * Clear cache VONA
     *
     * @return void
     */
    public function clearVonaCache(): void
    {
        Cache::tags(['fp-vona.index', 'api-vona.index'])->flush();
    }
}