<?php

namespace App\v1;

use Illuminate\Database\Eloquent\Model;

class MagmaSigertan extends Model
{
    protected $connection = 'magma';

    public $timestamps = false;

    protected $primaryKey = 'idx';

    protected $table = 'magma_sigertan';

    public function getQlsTglAttribute($value)
    {
        return explode('#',$value);
    }

    public function getQlsZkgAttribute($value)
    {
        return explode('#',$value);
    }
}
