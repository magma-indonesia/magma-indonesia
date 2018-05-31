<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class GempaResource extends JsonResource
{

    protected $codes = [
        'lts' => 'Letusan/Erupsi',
        'apl' => 'Awan Panas Letusan',
        'gug' => 'Guguran',
        'apg' => 'Awan Panas Guguran',
        'hbs' => 'Hembusan',
        'tre' => 'Tremor Non-Harmonik',
        'tor' => 'Tornillo',
        'lof' => 'Low Frequency',
        'hyb' => 'Hybrid/Fase Banyak',
        'vtb' => 'Vulkanik Dangkal',
        'vta' => 'Vulkanik Dalam',
        'vlp' => 'Very Long Period',
        'tel' => 'Tektonik Lokal',
        'trs' => 'Terasa',
        'tej' => 'Tektonik Jauh',
        'dev' => 'Double Event',
        'gtb' => 'Getaran Banjir',
        'hrm' => 'Harmonik',
        'dpt' => 'Deep Tremor',
        'mtr' => 'Tremor Menerus'
    ];
    
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $data = array();
        foreach ($this->codes as $code=>$name) {
            $collection = collect($this->$code);
            if ($collection->isNotEmpty())
            {
                $temp = [
                    'code' => $code,
                    'name' => $name,
                    'data' => $collection
                ];
                array_push($data,$temp);
            }
        }
        return $data;
    }
}
