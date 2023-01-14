<?php

namespace App\GerakanTanah;

class LewsData extends LewsModel
{
    protected $primaryKey = 'ID';

    protected $guarded = [
        'ID'
    ];

    protected $dates = [
        'date_data',
    ];
}
