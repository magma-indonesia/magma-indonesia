<?php

namespace App\WOVOdat;

use Illuminate\Database\Eloquent\Model;

class SeismicEvent extends Model
{
    protected $connection = 'wovo';

    protected $table = 'sd_evn';

    protected $primaryKey = 'sd_evn_id';

    public $timestamps = 'false';

    public function network()
    {
        return $this->belongsTo('App\WOVOdat\SeismicNetwork','sn_id','sn_id');
    }
}
