<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class VonaExerciseSubscriber extends Model
{
    protected $casts = [
        'status' => 'boolean'
    ];
    
    protected $table = 'vona_exercise_subscriber';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'status',
    ];
}
