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
            'code' => $this->code_id,
            'gunungapi' => $this->gunungapi->name,
            'noticenumber' => $this->noticenumber_id,
        ];
    }
}
