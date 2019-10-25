<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StatistikEvaluasi extends Model
{
    protected $fillable = [
        'code',
        'start',
        'end',
        'nip'
    ];

    protected $casts = [
        'start' => 'date',
        'end' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo('App\User','nip','nip');
    }
}
