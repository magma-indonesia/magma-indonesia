<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EqHbs extends Model
{
    use SoftDeletes;

    protected $table = 'e_hbs';

    protected $dates = ['deleted_at'];

    /* Data untuk gempa yang TIDAK memiliki nilai SP */        
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
        'deleted_at'
        
    ];

    protected $hidden   = [
        
        'id',
        'var_gempa_id',
        'noticenumber_id',
        'created_at',
        'updated_at',
        'deleted_at'
        
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
