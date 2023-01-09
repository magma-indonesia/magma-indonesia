<?php

namespace App\GerakanTanah;

use Illuminate\Database\Eloquent\Model;

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
