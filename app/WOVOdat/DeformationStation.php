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

    public function data()
    {
        return $this->hasMany('App\WOVOdat\DeformationData','ds_id','ds_id');
    }
}
