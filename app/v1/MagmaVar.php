<?php

namespace App\v1;

use App\v1\OldModelVar;

class MagmaVar extends OldModelVar
{
    protected $connection = 'magma';

    public $timestamps = false;

    protected $primaryKey = 'no';

    protected $table = 'magma_var';

    protected $fillable = [
        'var_nip_pemeriksa_pj',
        'var_nama_pemeriksa_pj',
    ];

    protected $casts = [
        'var_issued' => 'datetime:Y-m-d H:i:s',
        'var_log' => 'datetime:Y-m-d H:i:s',
        'var_data_date' => 'date:Y-m-d'
    ];

    
    // protected $dates = ['var_data_date'];
}
