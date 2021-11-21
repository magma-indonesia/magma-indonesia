<?php

namespace App\Http\Resources\v1;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\URL;

class MagmaSigertanResource extends JsonResource
{
    protected function signedUrl()
    {
        return URL::signedRoute('v1.gertan.sigertan.show', ['id' => $this->crs_ids]);
    }

    protected function deskripsi()
    {
        return 'Gerakan tanah terjadi di ' . $this->crs_vil . ', ' . $this->crs_rgn . ', ' . $this->crs_cty . ', ' . $this->crs_prv . ' pada ' . $this->crs_dtm->formatLocalized('%A, %d %B %Y') . ' pukul ' . $this->crs_dtm->format('H:i:s') . ' ' . $this->crs_zon . '. Secara Geografis, lokasi kejadian gerakan tanah terletak pada posisi ' . $this->crs_lat . ' LU dan ' . $this->crs_lon . ' BT.';
    }

    protected function raw()
    {
        return parent::toArray(request());
    }

    protected function isoDateTime()
    {
        switch ($this->crs_zon) {
            case 'WIB':
                $tz = 'Asia/Jakarta';
                break;
            case 'WITA':
                $tz = 'Asia/Makassar';
                break;
            default:
                $tz = 'Asia/Jayapura';
                break;
        }

        $isoDateTime = Carbon::createFromFormat('Y-m-d H:i:s', "{$this->crs_dtm}", $tz)
            ->toIso8601String();

        return $isoDateTime;
    }

    protected function description()
    {
        return [
            'id' => $this->crs_ids,
            'judul' => 'Laporan Tanggapan Gerakan Tanah di ' . $this->crs_vil . ', ' . $this->crs_rgn . ', ' . $this->crs_cty . ', ' . $this->crs_prv,
            'pelapor' => $this->crs_usr,
            'local_datetime' =>  $this->crs_dtm->format('Y-m-d H:i:s'),
            'updated_at' => $this->crs_log->format('Y-m-d H:i:s'),
            'time_zone' => $this->crs_zon,
            'iso_datetime' => $this->isoDateTime(),
            'latitude' =>  $this->crs_lat,
            'longitude' =>  $this->crs_lon,
            'provinsi' => $this->crs_prv,
            'kabupaten_kota' => $this->crs_cty,
            'kecamatan' => $this->crs_rgn,
            'kelurahan' => $this->crs_vil,
            'deskripsi' => $this->deskripsi(),
            'kerentanan' => empty($this->tanggapan->qls_zkg) ? null : 'Lokasi bencana berada pada Zona Potensi Gerakan Tanah ' . str_replace_last(', ', ' hingga ', title_case(implode(', ', $this->tanggapan->qls_zkg))) . '.',
            'rekomendasi' => empty($this->tanggapan->rekomendasi) ? null : nl2br($this->tanggapan->rekomendasi->qls_rec),
            'url' => URL::route('api.v1.home.gerakan-tanah.show', ['id' => $this->crs_ids]),
            'share' => [
                'url' =>  $this->signedUrl(),
                'description' => $this->deskripsi(),
            ]
        ];
    }

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        if ($request->has('raw')) {
            return $request->raw ? $this->raw() : $this->description();
        }

        return $this->description();
    }
}
