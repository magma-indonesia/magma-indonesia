<?php

namespace App\WOVOdat;

use Illuminate\Database\Eloquent\Model;

class GasStation extends Model
{
    protected $connection = 'wovo';

    protected $table = 'gs';

    protected $primaryKey = 'gs_id';

    public $timestamps = 'false';

    public function common_network()
    {
        return $this->belongsTo('App\WOVOdat\CommonNetwork','cn_id','cn_id');
    }

    public function gas_instrument()
    {
        return $this->hasOne('App\WOVOdat\GasInstrument','gs_id','gs_id');
    }
}
