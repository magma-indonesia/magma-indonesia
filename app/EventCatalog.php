<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EventCatalog extends Model
{
    protected $dates = [
        'p_datetime_utc',
        'p_datetime_local',
        's_datetime_utc',
        's_datetime_local',
    ];

    protected $guarded = ['id'];

    public function gunungapi()
    {
        return $this->belongsTo('App\Gadd', 'code_id', 'code');
    }

    public function seismometers()
    {
        return $this->belongsTo('App\Seismometer', 'scnl', 'scnl');
    }

    public function user()
    {
        return $this->belongsTo('App\User', 'nip', 'nip');
    }

    public function type()
    {
        return $this->belongsTo('App\EventType', 'code_event', 'code');
    }
}
