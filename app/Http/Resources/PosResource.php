<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PosResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'obscode' => $this->obscode,
            'nama' => $this->observatory, 
            'alamat' => $this->address,
            'elevation' => $this->elevation,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
        ];
    }
}
