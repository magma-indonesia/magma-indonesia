<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class VarKeteranganLain extends Model
{
    protected $fillable = [
        'noticenumber_id',
        'deskripsi'
    ];

    protected $hidden  = [
        'id',
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
