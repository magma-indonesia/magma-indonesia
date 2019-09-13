<?php

namespace App\WOVOdat;

use Illuminate\Database\Eloquent\Model;

class DeformationInstrument extends Model
{
    protected $connection = 'wovo';

    protected $table = 'di_gen';

    protected $primaryKey = 'di_gen_id';

    public $timestamps = 'false';

    public function deformation_station()
    {
        return $this->belongsTo('App\WOVOdat\DeformationStation','ds_id','ds_id');
    }
}
