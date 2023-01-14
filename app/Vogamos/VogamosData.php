<?php

namespace App\Vogamos;

class VogamosData extends VogamosModel
{
    protected $primaryKey = 'ID';

    protected $guarded = [
        'ID'
    ];

    protected $dates = [
        'date_data',
    ];
}
