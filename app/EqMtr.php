<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EqMtr extends Model
{
    use SoftDeletes;

    protected $table = 'e_mtr';

    protected $dates = ['deleted_at'];

    /* Data untuk gempa DOMINAN */
    protected $fillable = [
        
        'noticenumber_id',
        'jumlah',
        'amin',
        'amax',
        'adom',
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
