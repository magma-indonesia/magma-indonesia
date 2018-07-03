<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EqApl extends Model
{

    protected $table = 'e_apl';

    protected $casts = [
        'arah' => 'array'
    ];

    /* Data untuk gempa yang memiliki jarak LUNCUR */
    protected $fillable = [
        'var_gempa_id',
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
