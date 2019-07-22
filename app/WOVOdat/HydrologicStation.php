<?php

namespace App\WOVOdat;

use Illuminate\Database\Eloquent\Model;

class HydrologicStation extends Model
{
    protected $connection = 'wovo';

    protected $table = 'hs';

    protected $primaryKey = 'hs_id';

    public $timestamps = 'false';

    public function common_network()
    {
        return $this->belongsTo('App\WOVOdat\CommonNetwork','cn_id','cn_id');
    }
}
