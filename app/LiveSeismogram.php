<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LiveSeismogram extends Model
{
    protected $fillable = [
        'code',
        'seismometer_id',
        'filename'
    ];

    public function gunungapi()
    {
        return $this->belongsTo('App\Gadd','code','code');
    }

    public function seismometer()
    {
        return $this->belongsTo('App\Seismometer');
    }
}
