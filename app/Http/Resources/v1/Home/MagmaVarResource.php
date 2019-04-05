<?php

namespace App\Http\Resources\v1\Home;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Traits\VisualAsap;
use App\Traits\v1\DeskripsiGempa;

class MagmaVarResource extends JsonResource
{

    use VisualAsap,DeskripsiGempa;

    protected $visual;

    protected function setVisual($var)
    {
        $asap = (object) [
            'wasap' => isset($var->var_wasap) ? $var->var_wasap->toArray() : [],
            'intasap' => isset($var->var_wasap) ? $var->var_intasap->toArray() : [], 
            'tasap_min' => $var->var_tasap_min,
            'tasap_max' => $var->var_tasap,
        ];

        $this->visual = $this->visibility($var->var_visibility->toArray())
                            ->asap($var->var_asap, $asap)
                            ->cuaca($var->var_cuaca->toArray())
                            ->angin($var->var_kecangin->toArray(),$var->var_arangin->toArray())
                            ->getVisual();
        
        return $this;
    }

    protected function getKlimatologi($var)
    {
        return $this->clearVisual()->cuaca($var->var_cuaca->toArray())
            ->angin($var->var_kecangin->toArray(),$var->var_arangin->toArray())
            ->suhu($var->var_suhumin,$var->var_suhumax)
            ->kelembaban($var->var_kelembabanmin,$var->var_kelembabanmax)
            ->getVisual();
    }

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $this->setVisual($this->resource);

        return [
            'id' => $this->no,
            'gunungapi' => $this->ga_nama_gapi,
            'status' => $this->cu_status,
            'code' => $this->ga_code,
            'tanggal' => $this->var_data_date->formatLocalized('%A, %d %B %Y'),
            'periode' => $this->var_perwkt.' Jam, '.$this->periode.' '.$this->gunungapi->ga_zonearea,
            'rekomendasi' => $this->var_rekom,
            'visual' => $this->visual,
            'klimatologi' => $this->getKlimatologi($this->resource),
            'gempa' => $this->getDeskripsiGempa($this->resource),
        ];
    }
}
