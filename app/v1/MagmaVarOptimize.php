<?php

namespace App\v1;

use Illuminate\Database\Eloquent\Model;

class MagmaVarOptimize extends Model
{
    protected $connection = 'magma';

    public $timestamps = false;

    protected $primaryKey = 'no';

    protected $table = 'magma_var';
}
