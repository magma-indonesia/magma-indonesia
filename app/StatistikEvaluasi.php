<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class StatistikEvaluasi extends Model
{
    protected $fillable = [
        'code',
        'start',
        'end',
        'nip'
    ];

    protected $dates = [
        'start',
        'end',
    ];

    protected $appends = [
        'jumlah_hari'
    ];

    public function gunungapi()
    {
        return $this->belongsTo('App\Gadd','code','code');
    }

    public function user()
    {
        return $this->belongsTo('App\User','nip','nip');
    }

    public function getJumlahHariAttribute()
    {
        $start = Carbon::parse($this->attributes['start']);
        $end = Carbon::parse($this->attributes['end']);
        return (int) $end->diffInDays($start);
    }
}
