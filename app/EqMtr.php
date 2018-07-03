<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EqMtr extends Model
{

    protected $table = 'e_mtr';

    /* Data untuk gempa DOMINAN */
    protected $fillable = [
        'var_gempa_id',
        'noticenumber_id',
        'jumlah',
        'amin',
        'amax',
        'adom',
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
     *   Masing-masing Visual hanya dimiliki
     *   oleh 1 data VAR
     */
    public function var()
    {
        return $this->belongsTo('App\MagmaVars','noticenumber_id','noticenumber');
    }
}
