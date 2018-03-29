<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;

class LatestVarResource extends JsonResource
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
        return [
            'name'          => $this->name,
            'code'          => $this->code,
            'noticenumber'  => $this->latestVar->noticenumber,
            'issued'        => $this->latestVar->var_issued,
            'issued_day'    => $this->convertToDay($this->latestVar->var_issued, ' %H:%I:%S'),
            'data_date'     => $this->latestVar->var_data_date->toDateString(),
            'data_date_day' => $this->convertToDay($this->latestVar->var_data_date),
            'created'       => $this->latestVar->created_at,
            'created_day'   => $this->convertToDay($this->latestVar->created_at, ' %H:%I:%S'),
            'periode'       => intval($this->latestVar->var_perwkt),
            'pelapor'       => new UserResource($this->latestVar->user),
            'status'        => $this->latestVar->statuses_desc_id,
            'visual'        => new VisualResource($this->latestVar->visual),
            'klimatologi'   => new KlimatologiResource($this->latestVar->klimatologi),
            'gempa'         => new GempaResource($this->latestVar->gempa),
        ];
    }
}
