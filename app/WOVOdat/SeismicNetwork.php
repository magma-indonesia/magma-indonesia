<?php

namespace App\WOVOdat;

use Illuminate\Database\Eloquent\Model;

class SeismicNetwork extends Model
{
    protected $connection = 'wovo';

    protected $table = 'sn';

    protected $primaryKey = 'sn_id';

    public $timestamps = 'false';

    public function stations()
    {
        return $this->hasMany('App\WOVOdat\StationSeismic','sn_id','sn_id');
    }

    public function volcano()
    {
        return $this->belongsTo('App\WOVOdat\Volcano','vd_id','vd_id');
    }

    public function swarm()
    {
        return $this->hasMany('App\WOVOdat\IntervalSwarm','sn_id','sn_id');
    }

    /**
     * Interval Swarm
     *
     * @return void
     */
    public function events()
    {
        return $this->hasMany('App\WOVOdat\SeismicEvent','sn_id','sn_id');
    }
}
