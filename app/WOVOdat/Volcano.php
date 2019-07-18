<?php

namespace App\WOVOdat;

use Illuminate\Database\Eloquent\Model;

class Volcano extends Model
{
    protected $connection = 'wovo';

    protected $table = 'vd';

    protected $primaryKey = 'vd_id';

    public $timestamps = 'false';

    public function eruptions()
    {
        return $this->hasMany('App\WOVOdat\Eruption','vd_id','vd_id');
    }

    public function common_network()
    {
        return $this->hasMany('App\WOVOdat\CommonNetwork','vd_id','vd_id');
    }

    public function seismic_network()
    {
        return $this->hasOne('App\WOVOdat\SeismicNetwork','vd_id','vd_id');
    }

    public function stations()
    {
        return $this->hasManyThrough(
            'App\WOVOdat\StationSeismic',
            'App\WOVOdat\SeismicNetwork',
            'vd_id',
            'sn_id'
        );
    }

    public function events()
    {
        return $this->hasManyThrough(
            'App\WOVOdat\SeismicEvent',
            'App\WOVOdat\SeismicNetwork',
            'vd_id',
            'sn_id'
        );
    }

    public function information()
    {
        return $this->hasOne('App\WOVOdat\VolcanoInformation','vd_id','vd_id');
    }
}
