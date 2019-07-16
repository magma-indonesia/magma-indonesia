<?php

namespace App\WOVOdat;

use Illuminate\Database\Eloquent\Model;

class StationSeismic extends Model
{
    protected $connection = 'wovo';

    protected $table = 'ss';

    protected $primaryKey = 'ss_id';

    public $timestamps = 'false';

    public function network()
    {
        return $this->belongsTo('App\WOVOdat\SeismicNetwork','sn_id','sn_id');
    }

    public function contact()
    {
        return $this->belongsTo('App\WOVOdat\Contact','cc_id','cc_id');
    }

    public function ssam()
    {
        return $this->hasMany('App\WOVOdat\Ssam','ss_id','ss_id');
    }

    public function rsam()
    {
        return $this->hasManyThrough(
            'App\WOVOdat\Rsam',
            'App\WOVOdat\Ssam',
            'ss_id',
            'ss_id',
            'ss_id',
            'ss_id');
    }
}
