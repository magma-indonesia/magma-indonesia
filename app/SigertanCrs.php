<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Indonesia;

class SigertanCrs extends Model
{
   /**
    * Casting date attribute untuk beberapa kolom
    *
    * @var array
    */
    protected $dates = ['waktu_kejadian','tsc'];

    /**
     * Fillable columns
     *
     * @var array
     */
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

    /**
     * Hide from json array
     *
     * @var array
     */
    protected $hidden = [
        'id',
    ];

    protected $guarded  = [
        'id'
    ];

    /**
     * Menambahkan array dalam json
     *
     * @var array
     */
    protected $appends = ['provinsi','kota','kecamatan'];

    /**
     * Mutator untuk Tyoe attribute
     *
     * @param [type] $value
     * @return void
     */
    public function getTypeAttribute($value)
    {
        return title_case($value);
    }

    /**
     * Mutator untuk attribute provinsi
     *
     * @return void
     */
    public function getProvinsiAttribute()
    {
        return Indonesia::findProvince($this->province_id)->name;
    }

    /**
     * Mutator untuk attribute Kota
     *
     * @return void
     */
    public function getKotaAttribute()
    {
        return Indonesia::findCity($this->city_id)->name;
    }

    /**
     * Mutator untuk attribute Kecamatan
     *
     * @return void
     */
    public function getKecamatanAttribute()
    {
        return Indonesia::findDistrict($this->district_id)->name;
    }

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
