<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SigertanCrsPhoto extends Model
{
    protected $fillable = [
        'crs_id',
        'filename'
    ];

    protected $guarded =[
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
}
