<?php

namespace App\v1;

use Illuminate\Database\Eloquent\Model;

class MagmaVen extends Model
{
    protected $connection = 'magma';

    protected $table = 'magma_erupt';

    protected $primaryKey = 'erupt_id';
}
