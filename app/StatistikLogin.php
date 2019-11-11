<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StatistikLogin extends Model
{
    protected $fillable = [
        'date',
        'nip',
        'hit',
        'ip_address'
    ];

    protected $dates = [
        'date',
    ];

    public function user()
    {
        return $this->belongsTo('App\User','nip','nip');
    }
}
