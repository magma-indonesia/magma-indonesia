<?php

namespace App\WOVOdat;

use Illuminate\Database\Eloquent\Model;

class ThermalStation extends Model
{
    protected $connection = 'wovo';

    protected $table = 'ts';

    protected $primaryKey = 'ts_id';

    public $timestamps = 'false';

    public function common_network()
    {
        return $this->belongsTo('App\WOVOdat\CommonNetwork','cn_id','cn_id');
    }
}
