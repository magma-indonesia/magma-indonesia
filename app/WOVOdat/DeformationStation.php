<?php

namespace App\WOVOdat;

use Illuminate\Database\Eloquent\Model;

class DeformationStation extends Model
{
    protected $connection = 'wovo';

    protected $table = 'ds';

    protected $primaryKey = 'ds_id';

    public $timestamps = 'false';

    public function common_network()
    {
        return $this->belongsTo('App\WOVOdat\CommonNetwork','cn_id','cn_id');
    }

    public function deformation_instrument()
    {
        return $this->hasOne('App\WOVOdat\DeformationInstrument','ds_id','ds_id');
    }

    public function tilt()
    {
        return $this->hasMany('App\WOVOdat\DeformationTilt','ds_id','ds_id');
    }

    public function tilt_realtime()
    {
        return $this->hasMany('App\WOVOdat\DeformationTiltRealtime','ds_id','ds_id');
    }
}
