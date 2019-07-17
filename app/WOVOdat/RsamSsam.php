<?php

namespace App\WOVOdat;

use Illuminate\Database\Eloquent\Model;

class RsamSsam extends Model
{
    protected $connection = 'wovo';

    protected $table = 'sd_sam';

    protected $primaryKey = 'sd_sam_id';

    public $timestamps = 'false';

    public function station_seismic()
    {
        return $this->belongsTo('App\WOVOdat\StationSeismic','ss_id','ss_id');
    }

    public function rsam()
    {
        return $this->hasMany('App\WOVOdat\Rsam','sd_sam_id','sd_sam_id');   
    }
}
