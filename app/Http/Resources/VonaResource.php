<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class VonaResource extends JsonResource
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
            'uuid' => $this->uuid,
            'noticenumber' => $this->noticenumber,
            'issued' => $this->issued,
            'code_id' => $this->code_id,
            'smithsonian_id' => $this->gunungapi->smithsonian_id,
            'volcano' => $this->gunungapi->name,
            'cu_code' => $this->cu_code,
            'prev_code' => $this->prev_code,
            'location' => $this->location,
            'vas' => $this->vas,
            'vch_summit' => $this->vch_summit,
            'vch_asl' => $this->vch_asl,
            'vch_other' => $this->vch_other,
            'remarks' => $this->remarks,
            'issued_utc' => $this->issued_utc,
            'sent' => $this->sent ? true : false
        ];
    }
}
