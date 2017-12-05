<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PressPhoto extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'press_id', 
        'filename'
    ];

    public function press()
    {
        return $this->belongsTo('App\Press');
    }
}
