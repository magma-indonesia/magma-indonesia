<?php

namespace App\Http\Resources\v1;

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
        // return parent::toArray($request);
        return [
            'uuid' => $this->no,
            'noticenumber' => $this->notice_number,
            'issued' => $this->issued_time,
            'issued_utc' => $this->issued,
            'code_id' => $this->ga_code,
            'smithsonian_id' => $this->ga_id_smithsonian,
            'volcano' => $this->ga_nama_gapi,
            'cu_code' => $this->cu_avcode,
            'prev_code' => $this->pre_avcode,
            'location' => $this->volcano_location,
            'vas' => $this->volcanic_act_summ,
            'vch_summit' => $this->vc_height-$this->summit_elevation,
            'vch_asl' => $this->vc_height,
            'vch_other' => $this->other_vc_info,
            'remarks' => $this->remarks,
            'type' => $this->type,
            'sent' => $this->sent ? true : false
        ];
    }
}
