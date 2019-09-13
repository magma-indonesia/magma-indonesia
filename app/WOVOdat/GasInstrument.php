<?php

namespace App\WOVOdat;

use Illuminate\Database\Eloquent\Model;

class GasInstrument extends Model
{
    protected $connection = 'wovo';

    protected $table = 'gi';

    protected $primaryKey = 'gi_id';

    public $timestamps = 'false';

    public function gas_station()
    {
        return $this->belongsTo('App\WOVOdat\GasStation','gs_id','gs_id');
    }
}
