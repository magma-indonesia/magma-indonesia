<?php

namespace App\v1;

use Illuminate\Database\Eloquent\Model;

class History extends Model
{
    protected $connection = 'magma';

    public $timestamps = false;

    protected $primaryKey = 'id';

    protected $table = 'ga_history';

    protected $casts = [
        'ven_included' => 'boolean',
        'vona_included' => 'boolean'
    ];

    protected $fillable = [

        'ga_code',
        'intro',
        'ven_inlcuded',
        'vona_inlcuded',
        'created_at',
        'updated_at',

    ];

    public function gadd()
    {
        return $this->belongsTo('App\v1\Gadd','ga_code','ga_code');
    }
}
