<?php

namespace App\Traits;

use App\Gadd;
use App\Alat;

trait Peralatan
{
    public function getPeralatan()
    {
        return Gadd::has('alat')
                    ->with('alat.jenis')
                    ->orderBy('name')
                    ->get();
    }
}