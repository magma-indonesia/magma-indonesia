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
            'code_ga'   => $this->code_id,
            'gunungapi' => $this->gunungapi->name,
            'obscode'   => $this->obscode,
            'nama'      => $this->observatory,
            'alamat'    => $this->address
        ];
    }
}
