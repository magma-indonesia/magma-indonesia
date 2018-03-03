<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EqGug extends Model
{
    use SoftDeletes;

    protected $table = 'e_gug';

    protected $dates = ['deleted_at'];

    protected $casts = [
        
        'arah'         => 'array'
        
    ];

    /* Data untuk gempa yang memiliki jarak LUNCUR */
    protected $fillable = [
        
        'noticenumber_id',
        'jumlah',
        'amin',
        'amax',
        'dmin',
        'dmax',
        'rmin',
        'rmax',
        'arah',
        'created_at',
        'updated_at',
        'deleted_at'
        
    ];

    protected $hidden   = [
        
        'id',
        'noticenumber_id',
        
    ];
        
    protected $guarded  = [

        'id'

    ];

    /**     
     *   Masing-masing Visual hanya dimiliki
     *   oleh 1 data VAR
     */
    public function var()
    {
        return $this->belongsTo('App\MagmaVars','noticenumber_id','noticenumber');
    }
}
