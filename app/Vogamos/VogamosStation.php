<?php

namespace App\Vogamos;

use App\Traits\LewsChannelAlias;
use App\Traits\VogamosDataChannel;
use App\Vogamos\VogamosModel;

class VogamosStation extends VogamosModel
{
    protected $guarded = [
        'id'
    ];

    protected $dates = [
        'last_data_date',
    ];
}
