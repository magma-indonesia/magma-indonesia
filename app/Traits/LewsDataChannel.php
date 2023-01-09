<?php

namespace App\Traits;

use App\GerakanTanah\LewsData;
use Illuminate\Support\Collection;

trait LewsDataChannel
{
    public function getDataCountAttribute()
    {
        return (new LewsData())->setTable($this->attributes['ID_stasiun'])->count();
    }
}