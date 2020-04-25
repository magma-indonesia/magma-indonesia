<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BencanaGeologi extends Model
{
    protected $fillable = [
        'code',
        'urutan',
        'bencana_geologi_pendahuluan_id',
        'active'
    ];

    public function gunungapi()
    {
        return $this->belongsTo('App\Gadd','code','code');
    }

    public function pendahuluan()
    {
        return $this->belongsTo('App\BencanaGeologiPendahuluan','bencana_geologi_pendahuluan_id','id');
    }

    public function magma_var()
    {
        return $this->hasOne('App\v1\MagmaVar','ga_code','code');
    }

    public function vona()
    {
        return $this->hasOne('App\v1\Vona','ga_code','code')
        ->where('sent',1)
        ->orderByDesc('issued');
    }
}
