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
            'latitude'  => $this->latitude,
            'longitude' => $this->longitude,
            'elevation'     => $this->elevation,
            'latest_var'    => optional($this->latestVar)->noticenumber,
            'latest_ven'    => optional($this->latestVen)->uuid,
            'latest_vona'   => optional($this->latestVona)->uuid
            
        ];
    }
}
