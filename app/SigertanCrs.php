<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SigertanCrs extends Model
{
   
    protected $dates = ['waktu_kejadian'];

    protected $fillable = [
        'name',
        'phone',
        'crs_id',
        'waktu_kejadian',
        'zona',
        'type',
        'province_id',
        'city_id',
        'district_id',
        'village_id',
        'bwd',
        'latitude',
        'longitude',
        'brd',
        'sumber',
        'tsc',
        'ksc',
        'status',
        'latitude_user',
        'longitude_user'
    ];

    protected $hidden = [
        'id',
    ];

    protected $guarded  = [
        'id'
    ];

   /**     
     *   Masing-masing crs hanya memiliki 
     *   1 device yang digunakan untuk membuat laporan
     */
    public function device()
    {
        return $this->hasOne('App\SigertanCrsDevices','crs_id','id');
    }

   /**     
     *   Masing-masing crs hanya memiliki 
     *   1 photo laporan
     */
    public function photo()
    {
        return $this->hasOne('App\SigertanCrsPhotos','crs_id','id');
    }

   /**     
     *   Masing-masing crs hanya bisa
     *  divalidasi oleh 1 user Magma - Struktural
     */
    public function validator()
    {
        return $this->hasOne('App\SigertanCrsValidasi','crs_id','id');
    }
}
