<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StatistikResumeHarian extends Model
{
    protected $fillable = [
        'date',
        'nip',
        'hit'
    ];

    protected $dates = [
        'date',
    ];

    public function user()
    {
        return $this->belongsTo('App\User','nip','nip');
    }
}
