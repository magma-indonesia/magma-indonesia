<?php

namespace App\Http\Resources\v1;

use Illuminate\Support\Str;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\URL;

class MagmaRoqResource extends JsonResource
{
    protected function signedUrl()
    {
        return URL::signedRoute('v1.gempabumi.roq.show', $this);
    }

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->no,
            'judul' => Str::upper($this->roq_title),
            'deskripsi' => str_replace('Â°', ' ', $this->roq_intro),
            'rekomendasi' => $this->roq_rekom,
            'local_datetime' => $this->datetime_wib->format('Y-m-d H:i:s'),
            'time_zone' => 'WIB',
            'magnitude' => $this->magnitude,
            'kedalaman' => $this->depth,
            'latitude' => $this->lat_lima,
            'longitude' => $this->lon_lima,
            'lokasi' => $this->area,
            'kota_terdekat' => $this->koter,
            'intensitas' => $this->mmi,
            'berpotensi_tsunami' =>  (empty($this->roq_tsu) || $this->roq_tsu == 'TIDAK') ? true : false,
            'url' => URL::route('api.v1.home.gempa-bumi.show', ['id' => $this->no]),
            'share' => [
                'url' =>  $this->signedUrl(),
                'description' => Str::title($this->roq_title),
            ]
        ];
    }
}
