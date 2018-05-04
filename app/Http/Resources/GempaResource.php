<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class GempaResource extends JsonResource
{

    protected $codes = [
        'lts','apl','gug','apg','hbs',
        'tre','tor','lof','hyb','vtb',
        'vta','vlp','tel','trs','tej',
        'dev','gtb','hrm','dpt','mtr'
    ];
    
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {

        foreach ($this->codes as $code) {
            $temp[$code] = $this->when($this->$code, $this->$code);
        }

        return $temp;
    }
}
