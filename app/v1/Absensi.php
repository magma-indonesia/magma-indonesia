<?php

namespace App\v1;

use Illuminate\Database\Eloquent\Model;

class Absensi extends Model
{
    protected $connection = 'magma';

    public $timestamps = false;

    protected $primaryKey = 'id_abs';

    protected $table = 'pga_abs';
}
