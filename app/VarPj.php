<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class VarPj extends Model
{
    use SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $fillable = [
        'noticenumber_id',
        'nip_id'
    ];

    protected $guarded  = [
        'id'
    ];

    protected $hidden  = [
        'id',
        'noticenumber_id',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    /**     
     *   Masing-masing Var hanya dimiliki
     *   oleh 1 User
     * 
     *   @return \App\User 
     * 
     */
    public function user()
    {
        return $this->belongsTo('App\User','nip_id','nip');
    }
}
