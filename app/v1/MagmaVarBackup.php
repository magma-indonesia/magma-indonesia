<?php

namespace App\v1;

use Illuminate\Database\Eloquent\Model;

class MagmaVarBackup extends Model
{
    protected $connection = 'magma';

    public $timestamps = false;

    protected $primaryKey = 'no';

    protected $table = 'magma_var';

    protected $fillable = [
        'var_viskawah'
    ];
}
