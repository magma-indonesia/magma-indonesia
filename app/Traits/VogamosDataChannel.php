<?php

namespace App\Traits;

use App\Vogamos\VogamosData;

trait VogamosDataChannel
{
    public function getDataCountAttribute()
    {
        return (new VogamosData())->setTable($this->attributes['ID_stasiun'])->count();
    }
}
