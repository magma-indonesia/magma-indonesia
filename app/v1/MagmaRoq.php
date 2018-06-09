<?php

namespace App\v1;

use Illuminate\Database\Eloquent\Model;

class MagmaRoq extends Model
{
    public $timestamps = false;

    protected $connection = 'magma';

    protected $primaryKey = 'no';

    protected $table = 'magma_roq';

}
