<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuid;

class KameraGunungApi extends Model
{
    
    use Uuid;

    protected $fillable = [
        'uuid',
        'code',
        'lokasi',
        'url',
        'elevation',
        'latitude',
        'longitude',
        'hit',
        'publish',
    ];

    protected $casts = [
        'publish' => 'boolean'
    ];

    protected $appends = [
        'full_url',
    ];

    public function gunungapi()
    {
        return $this->belongsTo('App\Gadd','code','code');
    }

    public function getFullUrlAttribute()
    {
        return 'https://'.config('app.cctv_url').$this->attributes['url'];
    }
}
