<?php

namespace App\WOVOdat;

use Illuminate\Database\Eloquent\Model;

class Volcano extends Model
{
    protected $connection = 'wovo';

    protected $table = 'vd';

    protected $primaryKey = 'vd_id';

    public $timestamps = 'false';

    public function seismic_network()
    {
        return $this->hasOne('App\WOVOdat\SeismicNetwork','vd_id','vd_id');
    }

    public function information()
    {
        return $this->hasOne('App\WOVOdat\VolcanoInformation','vd_id','vd_id');
    }
}
