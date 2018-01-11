<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Gadd extends Model
{
    protected $table    = 'ga_dd';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'code',
        'alias',
        'tzone',
        'zonearea',
        'district',
        'province',
        'nearest_city',
        'division',
        'volc_type',
        'elevation',
        'latitude',
        'longitude',
        'smithsonian_id'
    ];

    /**     
     *   Masing-masing Gunungapi bisa memiliki lebih
     *   dari 1 Vars
     */
    public function var()
    {
        return $this->hasMany('App\MagmaVar','code_id','code');
    }

    /**     
     *   Masing-masing Gunungapi hanya memiliki 
     *   1 laporan harian
     */
    public function latestVar()
    {
        return $this->hasOne('App\MagmaVar','code_id','code')->orderBy('var_data_date','desc');
    }
}
