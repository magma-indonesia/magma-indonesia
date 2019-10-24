<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuid;

class Seismometer extends Model
{

    use Uuid;

    protected $fillable = [
        'uuid',
        'code',
        'lokasi',
        'station',
        'channel',
        'network',
        'location',
        'scnl',
        'elevation',
        'latitude',
        'longitude',
        'is_active',
        'hit',
        'published',
    ];

    protected $casts = [
        'published' => 'boolean',
        'is_active' => 'boolean',
    ];

    protected $appends = [
        'full_url',
    ];

    public function gunungapi()
    {
        return $this->belongsTo('App\Gadd','code','code');
    }

    public function live_seismogram()
    {
        return $this->hasOne('App\LiveSeismogram');
    }

    public function getFullUrlAttribute()
    {
        return config('app.winston_url').':'.config('app.winston_port').'/heli?code='.$this->attributes['scnl'].'&w=1200&h=720&lb=1';
    }

}
