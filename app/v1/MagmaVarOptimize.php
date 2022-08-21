<?php

namespace App\v1;

use Illuminate\Database\Eloquent\Model;

class MagmaVarOptimize extends Model
{
    protected $connection = 'magma';

    public $timestamps = false;

    protected $primaryKey = 'no';

    protected $table = 'magma_var';

    public function user()
    {
        return $this->belongsTo('App\v1\User', 'var_nip_pelapor', 'vg_nip');
    }

    public function gunungapi()
    {
        return $this->belongsTo('App\v1\Gadd', 'ga_code', 'ga_code');
    }
}
