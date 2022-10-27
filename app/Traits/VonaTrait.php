<?php

namespace App\Traits;

use App\v1\Gadd;
use App\Vona;
use App\v1\Vona as VonaOld;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;

trait VonaTrait
{

    protected $feet = 3.2;

    /**
     * Get Noticenumber of VONA
     *
     * @param Request $request
     * @return String
     */
    protected function noticenumber(Request $request): string
    {
        $year = now()->format('Y');
        $code = $request->code;
        $prefix = $request->type == 'real' ? '' : 'EXERCISE-';

        $vonaCount = VonaOld::where('issued', 'like', '2022%')
            ->where('ga_code', $code)
            ->where('type', $request->type)
            ->count();

        $vonaCount = sprintf('%03d', $vonaCount + 1);
        return $prefix . $year . $code . $vonaCount;
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
    protected function previousCode(Request $request): string
    {
        $latestVona = VonaOld::with('volcano:ga_code,ga_nama_gapi,ga_id_smithsonian,ga_elev_gapi,ga_lon_gapi,ga_lat_gapi,ga_prov_gapi,ga_zonearea')
            ->where('ga_code', $request->code)
            ->where('type', $request->type)
            ->where('sent', 1)
            ->orderBy('issued_time', 'desc')
            ->first();

        if (is_null($latestVona))
            return 'unassigned';

        return $latestVona->cu_avcode;
    }

    /**
     * Get vona color code
     *
     * @param Request $request
     * @return string
     */
    protected function getColor(Request $request): string
    {
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

        if ($request->height >= 6000)
            return 'RED';

        if ($request->height > 0)
            return 'ORANGE';

        return 'YELLOW';
    }

    /**
     * Get vona current coior code
     *
     * @param Request $request
     * @return string
     */
    protected function currentCode(Request $request): string
    {
        return $request->color === 'auto' ?
                $this->getColor($request) : strtoupper($request->color);
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
        return "{$this->convertLatitude($vona->gunungapi->latitude)} {$this->convertLongitude($vona->gunungapi->latitude)}";
    }

    /**
     * Get text summit elevation
     *
     * @param integer $elevation
     * @return string
     */
    protected function summitElevation(int $elevation): string
    {
        $feet = round($elevation * $this->feet);
        return "{$feet} FT ({$elevation}) M";
    }

    /**
     * Next notice
     *
     * @return void
     */
    protected function nextNotice()
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
            'notice_number' => $this->noticenumber($request),
            'volcano_location' => $this->location($vona),
            'area' => "{$vona->gunungapi->province_en}, Indonesia",
            'summit_elevation' => $this->summitElevation($vona->gunungapi->elevation),
            'volcanic_act_summ' => $this->volcanoActivitySummary($vona),
            'vc_height' => $this->ashCloudHeight($vona),
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

    /**
     * Get text for ash cloud height in feet and meter
     *
     * @param Vona $vona
     * @return string
     */
    protected function ashCloudHeight(Vona $vona): string
    {
        $feet = round($vona->ash_height * $this->feet);
        return "{$feet} FT ({$vona->ash_height} M)";
    }

    /**
     * Get text for Volcanic Cloud Height
     *
     * @param Vona $vona
     * @return string
     */
    protected function volcanicCloudHeight(Vona $vona): string
    {
        $deskripsi = $vona->ash_height == 0 ?
            "Ash-cloud is not observed." :
            "Best estimate of ash-cloud top is around {$this->ashCloudHeight($vona)} above sea level, may be higher than what can be observed clearly. Source of height data: ground observer.";

        return $deskripsi;
    }

    /**
     * Get Volcanic Activity Summary
     *
     * @param Vona $vona
     * @return string
     */
    protected function volcanoActivitySummary(Vona $vona): string
    {
        $utc = $vona->issued->format('Hi');
        $tz = $this->zoneArea($vona->gunungapi->zonearea);
        $local = Carbon::createFromTimeString($vona->issued, 'UTC')->setTimezone($tz)->format('Hi');

        if ($vona->is_visible) {
            return "Eruption with volcanic ash cloud at {$utc} UTC ({$local} local)";
        }

        if ($vona->amplitude > 0 || $vona->amplitude_tremor > 0) {
            return "Eruption at {$utc} UTC ({$local} local)";
        }

        return "Ash-cloud is not observed.";
    }

    /**
     * Other Volcanic Cloud Information
     *
     * @param Vona $vona
     * @return string
     */
    protected function otherVolcanicCloudInformation(Vona $vona): string
    {
        if ($vona->ash_height == 0) {
            return "Ash-cloud is not observed.";
        };

        $direction = __($vona->ash_directions[0]);
        return "Ash cloud moving to {$direction}";
    }

    /**
     * Get text if eruption is continuing
     *
     * @param Vona $vona
     * @return string
     */
    protected function eruptionIsContinuing(Vona $vona): string
    {
        return $vona->is_continuing ? 'Eruption and ash emission is continuing.' : '';
    }

    /**
     * Get text for tremor
     *
     * @param Vona $vona
     * @return string
     */
    protected function eruptionTremor(Vona $vona): string
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
    protected function eruptionRecording(Vona $vona): string
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
    protected function remarks(Vona $vona): string
    {
        $eruptionIsContinuing = $this->eruptionIsContinuing($vona);
        $eruptionRecording = $this->eruptionRecording($vona);
        $eruptionTremor = $this->eruptionTremor($vona);
        $remarks = $vona->remarks;

        return "{$eruptionIsContinuing} {$eruptionRecording} {$eruptionTremor} {$remarks}";
    }
}