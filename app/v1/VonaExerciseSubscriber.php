<?php

namespace App\v1;

use Illuminate\Database\Eloquent\Model;

class VonaExerciseSubscriber extends Model
{
    protected $connection = 'magma';

    protected $table = 'magma_subscribe_exer';

    protected $primaryKey = 'no';

    public function getNamaAttribute($value)
    {
        return !empty($value) ? $value : 'Guest';
    }
}
