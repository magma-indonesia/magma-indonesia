<?php

namespace App\Http\Resources\v1;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\URL;

class VonaResource extends JsonResource
{
    protected function signedUrl()
    {
        return URL::signedRoute('v1.vona.show', ['id' => $this->no]);
    }

    protected function description()
    {
        return ucfirst($this->volcanic_act_summ) . ' ' . $this->vc_height_text . ' ' . $this->other_vc_info;
    }

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
            'sent' => $this->sent ? true : false,
            'volcano_information' => [
                'code' => $this->volcano->ga_code,
                'name' => $this->volcano->ga_nama_gapi,
                'latitude' => $this->volcano->ga_lat_gapi,
                'longitude' => $this->volcano->ga_lon_gapi,
                'elevation' => $this->volcano->ga_elev_gapi,
            ],
            'description' => $this->description(),
            'share' => [
                'url' =>  $this->signedUrl(),
                'description' => $this->description(),
            ]
        ];
    }
}
