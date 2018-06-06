<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class VonaCollection extends ResourceCollection
{
    /**
     * Get additional data that should be returned with the resource array.
     *
     * @param \Illuminate\Http\Request  $request
     * @return array
     */
    public function with($request)
    {
        return [
            'meta' => [
                'deskripsi' => [
                    'source' => 'Pusat Vulkanologi dan Mitigasi Bencana Geologi',
                    'code_id' => 'Keterangan kode gunung api. Kode ini digunakan oleh PVMBG, dan tidak bersifat universal',
                    'cu_code' => 'Current Aviation Color Code, kode warna VONA terkini. Dengan urutan level tertinggi ke rendah, RED, ORANGE, YELLOW, dan GREEN',
                    'prev_code' => 'Previous Aviation Color Code, kode warna VONA yang dikeluarkan untuk noticenumber sebelumnya',
                    'location' => 'Lokasi koordinat gunung api',
                    'vas' => 'Volcanic Activity Summary, ringkasan kejadian letusan gunung api. Meliputi waktu terjadinya letusan (Local dan UTC) serta tinggi letusan',
                    'vch_summit' => 'Volcano Cloud Height (VCH) Above Summit, tinggi kolom abu letusan dalam satuan meter dihitung dari atas puncak (berdasarkan pengukuran di darat)',
                    'vch_asl' => 'Volcano Cloud Height (VCH) Above Sea Level, tinggi kolom abu letusan dalam satuan ,meter dihitung di atas permukaan laut',
                    'vch_other' => 'Memberikan keterangan lainnya terkait kolom abu letusan, bisa meliputi arah, warna ataupun intensitas letusan',
                    'remarks' => 'Keterangan tambahan yang tidak berhubungan dengan kolom abu letusan, seperti kegempaan maupun keterangan visual lainnya'
                ]
            ],
        ];
    }
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return VonaResource::collection($this->collection);
    }
}
