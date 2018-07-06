<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;

class VarResource extends JsonResource
{
    private function convertToDay($request, $time = null)
    {
        return Carbon::createFromFormat('Y-m-d H:i:s', $request)->formatLocalized('%A, %d %B %Y'.$time);
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

            'id'                => $this->id,
            'noticenumber'      => $this->noticenumber,
            'pos'               => new PosResource($this->pos),
            'gunungapi'         => new GunungApiResource($this->gunungapi),
            'status'            => $this->status,
            'status_deskripsi'  => $this->status_deskripsi,
            'data_date'         => $this->var_data_date->toDateString(),
            'data_date_day'     => $this->convertToDay($this->var_data_date),
            'created'           => $this->created_at,
            'created_day'       => $this->convertToDay($this->created_at, ' %H:%I:%S'),
            'periode'           => intval($this->var_perwkt),
            'pelapor'           => new UserResource($this->user),
            'visual'            => new VisualResource($this->visual),
            'klimatologi'       => new KlimatologiResource($this->klimatologi),
            'gempa'             => new GempaResource($this->gempa),
            
        ];
    }
    
}
