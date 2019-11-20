<?php

namespace App\v1;

use Illuminate\Database\Eloquent\Model;

class StatistikMagmaVen extends Model
{
    protected $connection = 'magma';

    protected $table = 'statistik_magma_erupt';

    protected $fillable = [
        'ga_code',
        'erupt_id',
        'hit',
    ];

    public function gunungapi()
    {
        return $this->belongsTo('App\v1\Gadd','ga_code','ga_code');
    }

    public function ven()
    {
        return $this->belongsTo('App\v1\MagmaVen','erupt_id','erupt_id');
    }
}
