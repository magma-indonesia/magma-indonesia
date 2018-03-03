<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class VarKlimatologi extends Model
{
    use SoftDeletes;

    protected $dates = ['deleted_at'];
    
    protected $casts = [

        'cuaca'         => 'array',
        'kecangin'      => 'array',
        'arahangin'     => 'array'
        
    ];

    protected $fillable = [

        'noticenumber_id',
        'cuaca',
        'kecangin',
        'arahangin',
        'suhumin',
        'suhumax',
        'lembabmin',
        'lembabmax',
        'tekmin',
        'tekmax',
        'created_at',
        'updated_at',
        'deleted_at'

    ];

    protected $hidden  = [
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
