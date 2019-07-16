<?php

namespace App\WOVOdat;

use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    protected $connection = 'wovo';

    protected $table = 'cc';

    protected $primaryKey = 'cc_id';

    public $timestamps = 'false';

    public function seismic_stations()
    {
        return $this->hasMany('App\WOVOdat\Station','cc_id','cc_id');
    }
}
