<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StatistikLoginVar extends Model
{
    protected $guarded = ['id'];

    protected $dates = [
        'date',
    ];

    public function user()
    {
        return $this->belongsTo('App\User', 'nip', 'nip');
    }
}
