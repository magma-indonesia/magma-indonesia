<?php

namespace App\v1;

use Illuminate\Database\Eloquent\Model;

class MagmaVar extends Model
{
    protected $connection = 'magma';

    public $timestamps = false;

    protected $primaryKey = 'no';

    protected $table = 'magma_var';

    protected $fillable = [
        'var_nip_pemeriksa_pj',
        'var_nama_pemeriksa_pj'
    ];
}
