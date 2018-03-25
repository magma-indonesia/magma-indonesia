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
     * Get additional data that should be returned with the resource array.
     *
     * @param \Illuminate\Http\Request  $request
     * @return array
     */
    public function with($request)
    {
        return [
            'meta' => [
                'id'            => $this->id,
                'gunungapi'     => new GunungApiResource($this->gunungapi),
                'noticenumber'  => $this->noticenumber,
                'issued'        => $this->var_issued,
                'issued_day'    => $this->convertToDay($this->var_issued, ' %H:%I:%S'),
                'data_date'     => $this->var_data_date->toDateString(),
                'data_date_day' => $this->convertToDay($this->var_data_date),
                'created'       => $this->created_at,
                'created_day'   => $this->convertToDay($this->created_at, ' %H:%I:%S'),
                'periode'       => intval($this->var_perwkt),
                'pelapor'       => new UserResource($this->user),
            ],
        ];
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
            
            'status'        => $this->statuses_desc_id,
            'gempa'         => new GempaResource($this->gempa),
            'visual'        => new VisualResource($this->visual),
            'klimatologi'   => new KlimatologiResource($this->klimatologi),
            
        ];
    }
    
}
