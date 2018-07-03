<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EqTor extends Model
{

    protected $table = 'e_tor';

    /* Data untuk gempa yang memiliki nilai SP */            
    protected $fillable = [
        'var_gempa_id',
        'noticenumber_id',
        'jumlah',
        'amin',
        'amax',
        'dmin',
        'dmax',
        'created_at',
        'updated_at',
    ];

    protected $hidden   = [
        'id',
        'var_gempa_id',
        'noticenumber_id',
        'created_at',
        'updated_at',
    ];
        
    protected $guarded  = [
        'id',
        'var_gempa_id',
        'noticenumber_id',
    ];

    /**     
     *   Masing-masing gempa hanya dimiliki
     *   oleh 1 data VAR
     */
    public function var()
    {
        return $this->belongsTo('App\MagmaVars','noticenumber_id','noticenumber');
    }
}
