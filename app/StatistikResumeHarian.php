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
        'updated_at'
    ];

    public function user()
    {
        return $this->belongsTo('App\User','nip','nip');
    }
}
