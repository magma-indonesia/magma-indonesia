<?php

namespace App\v1;

use Illuminate\Database\Eloquent\Model;

class Kantor extends Model
{
    protected $connection = 'magma';

    public $timestamps = false;

    protected $primaryKey = 'id';

    protected $table = 'pga_pos';
}
