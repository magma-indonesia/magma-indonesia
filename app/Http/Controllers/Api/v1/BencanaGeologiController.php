<?php

namespace App\Http\Controllers\Api\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Traits\v1\DeskripsiGerakanTanah;
use App\Traits\v1\DeskripsiLetusan;
use App\v1\GertanCrs;
use App\v1\MagmaRoq;
use App\v1\MagmaVen;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class BencanaGeologiController extends Controller
{
    use DeskripsiLetusan;
    use DeskripsiGerakanTanah;

    /**
     * Respons data
     *
     * @param string $jenis_bencana
     * @param string $id
     * @param string|null $photo
     * @param string $description
     * @param string $datetime
     * @param string $timeZone
     * @return array
     */
    protected function data(
        string $jenis_bencana,
        string $id,
        ?string $photo = null,
        string $description,
        string $datetime,
        string $timeZone,
        float $latitude,
        float $longitude
    ): array
    {
        $carbonDatetime = Carbon::createFromFormat('Y-m-d H:i:s', $datetime);

        return [
            'jenis_bencana' => $jenis_bencana,
            'id' => $id,
            'photo' => $photo,
            'description' => $description,
            'utc_datetime' => $this->utcDatetime($datetime, $this->timeZone($timeZone)),
            'local_datetime' => $datetime,
            'local_date' => $carbonDatetime->format('Y-m-d'),
            'local_time' => $carbonDatetime->format('H-i-s'),
            'time_zone' => $timeZone,
            'text_datetime' => $carbonDatetime->formatLocalized('%A, %d %B %Y'),
            'human_datetime' => $carbonDatetime->diffForHumans(),
            'latitude' => $latitude,
            'longitude' => $longitude,
        ];
    }

    /**
     * Convert local Time Zone
     *
     * @param string $timeZone
     * @return string
     */
    protected function timeZone(string $timeZone): string
    {
        switch ($timeZone) {
            case 'WIB':
                return 'Asia/Jakarta';
            case 'WITA':
                return 'Asia/Makassar';
        }

        return 'Asia/Jayapura';
    }

    /**
     * Get datetime in UTC format
     *
     * @param string $datetime
     * @param string $timeZone
     * @return string
     */
    protected function utcDatetime(string $datetime, string $timeZone): string
    {
        $datetime_utc = Carbon::createFromTimeString($datetime, $timeZone)
                            ->setTimezone('UTC')->format('Y-m-d H:i:s');

        return $datetime_utc;
    }

    /**
     * Get latest kejadian gempa bumi
     *
     * @return array
     */
    protected function kejadianGempaBumi(): array
    {
        $magmaRoq = MagmaRoq::orderBy('datetime_wib', 'desc')->first();

        return $this->data(
            'gempa-bumi',
            $magmaRoq->no,
            null,
            str_replace('Â°',' ', $magmaRoq->roq_intro),
            $magmaRoq->datetime_wib->format('Y-m-d H:i:s'),
            'WIB',
            $magmaRoq->lat_lima,
            $magmaRoq->lon_lima
        );
    }

    /**
     * Get latest kejadian gerakan tanah
     *
     * @return array
     */
    protected function kejadianGerakanTanah(): array
    {
        $gertanCrs = GertanCrs::has('tanggapan')
            ->with('tanggapan')
            ->where('crs_sta', 'TERBIT')
            ->whereBetween('crs_lat', [-12, 2])
            ->whereBetween('crs_lon', [89, 149])
            ->orderBy('crs_log', 'desc')
            ->first();

        return $this->data(
            'gerakan-tanah',
            $gertanCrs->crs_ids,
            empty($gertanCrs->tanggapan->qls_pst) ? null : $gertanCrs->tanggapan->qls_pst,
            $this->deskripsiGerakanTanah($gertanCrs),
            $gertanCrs->crs_dtm->format('Y-m-d H:i:s'),
            $gertanCrs->crs_zon,
            $gertanCrs->crs_lat,
            $gertanCrs->crs_lon
        );
    }

    /**
     * Get kejadian letusan gunung api
     *
     * @return array
     */
    protected function letusanGunungApi(): array
    {
        $ven = MagmaVen::with('gunungapi:ga_code,ga_nama_gapi,ga_zonearea,ga_lat_gapi,ga_lon_gapi,ga_elev_gapi')->lastVen()->first();

        return $this->data(
            'gunung-api',
            $ven->erupt_id,
            $ven->erupt_pht ?: null,
            $this->deskripsiTwitter($ven),
            "$ven->erupt_tgl $ven->erupt_jam:00",
            $ven->gunungapi->ga_zonearea,
            $ven->gunungapi->ga_lat_gapi,
            $ven->gunungapi->ga_lon_gapi,
        );
    }

    /**
     * Undocumented function
     *
     * @return array
     */
    public function index(): array
    {
        return collect([
            $this->letusanGunungApi(),
            $this->kejadianGerakanTanah(),
            $this->kejadianGempaBumi(),
        ])->sortByDesc('utc_datetime')->values()->all();
    }
}
