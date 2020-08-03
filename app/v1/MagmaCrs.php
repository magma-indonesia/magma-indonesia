<?php

namespace App\v1;

use Illuminate\Database\Eloquent\Model;

class MagmaCrs extends Model
{
    protected $connection = 'magma';

    public $timestamps = false;

    protected $primaryKey = 'idx';

    protected $table = 'magma_crs';

    protected $dates = [
        'crs_dtm',
        'crs_log',
        'crs_tsc'
    ];

    public function tangapan()
    {
        return $this->hasOne('App\v1\MagmaSigertan','crs_ids','crs_ids');
    }
}
