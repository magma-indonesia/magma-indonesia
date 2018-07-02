<?php

namespace App\v1;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    protected $connection = 'magma';

    public $timestamps = false;

    protected $primaryKey = 'id';

    protected $table = 'vg_peg';

}
