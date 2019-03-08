<?php

namespace App\Traits;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\TempTable;

trait ExportHelper 
{
    
    protected function getStartDate()
    {
        $start = TempTable::select('others')->where('jenis','restore')->first();
        return empty($start) ? '2016-05-01' : $start->others;
    }

    protected function setStartDate($var_data_date)
    {
        $temp = TempTable::updateOrCreate(
            [   'jenis' => 'restore' ],
            [   'others' => $var_data_date]
        );

        return $temp;
    }

    protected function setNoticeNumber()
    {
        $this->noticenumber = $this->item->var_perwkt == '24' 
                                ? str_replace('-','',$this->item->var_data_date->format('Y-m-d')).'2400'
                                : str_replace('-','',$this->item->var_data_date->format('Y-m-d'))
                                    .substr($this->item->periode,0,2).'00';
        return $this;
    }

    protected function setStatus()
    {
        switch ($this->item->status) {
            case '1':
                $this->status = 'Level I (Normal)';
                return $this;
            case '2':
                $this->status = 'Level II (Waspada)';
                return $this;
            case '3':
                $this->status = 'Level III (Siaga)';
                return $this;
            default:
                $this->status = 'Level IV (Awas)';
                return $this;
        }
    }

    protected function setTasapMin()
    {
        $this->tasap_min = optional($this->item->visual)->asap
                            ? $this->item->visual->asap->tasap_min
                            : '0';

        return $this;
    }

    protected function setTasapMax()
    {
        $this->tasap_max = optional($this->item->visual)->asap
                            ? $this->item->visual->asap->tasap_max 
                            : '0';

        return $this;
    }

    protected function setWasap()
    {
        $this->wasap = optional($this->item->visual)->asap
                        ? implode(', ' ,$this->item->visual->asap->wasap)
                        : '-';

        return $this;
    }

    protected function setIntasap()
    {
        $this->intasap = optional($this->item->visual)->asap
                            ? implode(', ' ,$this->item->visual->asap->intasap)
                            : '-';
        
        return $this;
    }

    protected function setTekasap()
    {
        $this->tekasap = optional($this->item->visual)->asap
                            ? $this->item->visual->asap->tekasap ?? ['-'] : ['-'];

        $this->tekasap = implode(', ',$this->tekasap);
        return $this;
    }
}