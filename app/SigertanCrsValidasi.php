<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SigertanCrsValidasi extends Model
{
    protected $touches = ['crs'];

    protected $fillable = [
        'crs_id',
        'valid',
        'nip_id'
    ];

    protected $guarded = [
        'id'
    ];

    /**     
     *   Masing-masing crs hanya dimiliki
     *   oleh 1 data crs
     */
    public function crs()
    {
        return $this->belongsTo('App\SigertanCrs','crs_id','id');
    }

    /**     
     *   Masing-masing crs hanya dimiliki
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
