<?php

namespace App\Vogamos;

use Illuminate\Database\Eloquent\Model;

class VogamosModel extends Model
{
    protected $connection = 'vogamos';

    public $timestamps = false;
}