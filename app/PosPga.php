<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PosPga extends Model
{
    protected $fillable = [

        'code_id',
        'obscode',
        'observatory',
        'address',
        'elevation',
        'latitude',
        'longitude',
        'created_at',
        'updated_at'

    ];

    /**     
     *   Masing-masing Pos hanya dimiliki
     *   oleh 1 Gunungapi
     * 
     *   @return \App\Gadd
     * 
     */
    public function gunungapi()
    {
        return $this->belongsTo('App\Gadd','code_id','code');
    }
}
