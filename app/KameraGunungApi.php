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

    public function gunungapi()
    {
        return $this->belongsTo('App\Gadd','code','code');
    }
}
