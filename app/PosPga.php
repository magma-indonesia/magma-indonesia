<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PosPga extends Model
{
    
    protected $fillable = [
        'code_id',
        'obscode',
        'keterangan',
    ];

    protected $appends = [
        'observatory',
        'address',
        'elevation',
        'latitude',
        'longitude',
    ];

    public function getObservatoryAttribute()
    {
        return $this->kantor->nama;
    }

    public function getAddressAttribute()
    {
        return $this->kantor->address;
    }

    public function getElevationAttribute()
    {
        return $this->kantor->elevation;
    }

    public function getLatitudeAttribute()
    {
        return $this->kantor->latitude;
    }

    public function getLongitudeAttribute()
    {
        return $this->kantor->longitude;
    }

    /**     
     *   Masing-masing Pos hanya dimiliki
     *   oleh 1 Gunungapi
     * 
     *   @return \App\Gadd
     * 
     */
    public function gunungapi()
    {
        return $this->belongsTo('App\Gadd','code_id','code');
    }

    /**     
     *   Masing-masing Pos hanya dimiliki
     *   oleh 1 Gunungapi
     * 
     *   @return \App\Gadd
     * 
     */
    public function kantor()
    {
        return $this->belongsTo('App\Kantor','obscode','code');
    }
}
