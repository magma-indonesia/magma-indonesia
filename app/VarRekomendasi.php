<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class VarRekomendasi extends Model
{
    protected $fillable = [
        'code_id',
        'status',
        'rekomendasi',
    ];

    protected $guard = [
        'id'
    ];

    /**     
     *   Masing-masing Var hanya dimiliki
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
