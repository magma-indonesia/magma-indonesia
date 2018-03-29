<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class GunungApiResource extends JsonResource
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
            'name'      => $this->name,
            'code'      => $this->code,
            'tzone'     => $this->tzone,
            'tarea'     => $this->zonearea,
            'address'   => $this->district.', '.$this->province,
            'latest'    => $this->latestVar->noticenumber,
        ];
    }
}
