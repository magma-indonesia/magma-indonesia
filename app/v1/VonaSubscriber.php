<?php

namespace App\v1;

use Illuminate\Database\Eloquent\Model;

class VonaSubscriber extends Model
{
    protected $connection = 'magma';

    protected $table = 'magma_subscribe';

    protected $primaryKey = 'no';

    public function getNamaAttribute($value)
    {
        return !empty($value) ? $value : 'Guest';
    }
}
