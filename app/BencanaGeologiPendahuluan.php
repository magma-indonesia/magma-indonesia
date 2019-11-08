<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BencanaGeologiPendahuluan extends Model
{
    protected $fillable = [
        'code',
        'pendahuluan',
    ];

    public function gunungapi()
    {
        return $this->belongsTo('App\Gadd','code','code');
    }
}
