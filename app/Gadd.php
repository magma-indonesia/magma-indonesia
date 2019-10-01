<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class Gadd extends Model
{
    protected $table = 'ga_dd';

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

    protected $hidden = [
        'id',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    /**     
     *   Masing-masing Gunungapi bisa memiliki lebih
     *   dari 1 Status. Catatan peningkatan atau penurunan level Gunung Api
     */
    public function status()
    {
        return $this->hasMany('App\Status','code_id','code');
    }

    /**     
     *   Masing-masing Gunungapi bisa memiliki lebih
     *   dari 1 Pos Pengamatan Gunung Api
     */
    public function pos()
    {
        return $this->hasMany('App\PosPga','code_id','code');
    }

    /**     
     *   Masing-masing Gunungapi bisa memiliki lebih
     *   dari 1 Vars
     */
    public function var()
    {
        return $this->hasMany('App\MagmaVar','code_id','code');
    }

    /**     
     *   Masing-masing Gunungapi bisa memiliki lebih
     *   dari 1 VEN
     */
    public function ven()
    {
        return $this->has('App\MagmaVen','code_id','code');
    }

    /**     
     *   Masing-masing Gunungapi hanya memiliki 
     *   1 laporan harian
     */
    public function latest_vars()
    {
        return $this->hasOne('App\MagmaVar','code_id','code')
                    ->join(DB::raw('(SELECT code_id, MAX(noticenumber) as noticenumber FROM magma_vars GROUP BY code_id) lates_vars'), function($join) {
                        $join->on('magma_vars.noticenumber','=','lates_vars.noticenumber');
                    });
    }

    /**     
     *   Masing-masing Gunungapi hanya memiliki 
     *   1 laporan VEN Terkini
     */
    public function latest_vens()
    {
        return $this->hasOne('App\MagmaVen','code_id','code')
                    ->join(DB::raw('(SELECT code_id, MAX(date) as date FROM magma_vens GROUP BY code_id) latest_vens'), function($join) {
                        $join->on('magma_vens.date','=','latest_vens.date')
                            ->on('magma_vens.code_id','=','latest_vens.code_id');
                    });
    }

    /**     
     *   Masing-masing Gunungapi hanya memiliki 
     *   1 laporan VONA Terkini
     */
    public function latest_vona()
    {
        return $this->hasOne('App\Vona','code_id','code')
                    ->join(DB::raw('(SELECT code_id, MAX(issued) as issued FROM vonas GROUP BY code_id) latest_vona'), function($join) {
                        $join->on('vonas.issued','=','latest_vona.issued')
                            ->on('vonas.code_id','=','latest_vona.code_id');
                    });
    }

    /**
     * Masing-masing gunungapi memiliki rekomendasi
     *
     * @return void
     */
    public function rekomendasi()
    {
        return $this->hasMany('App\VarRekomendasi','code_id','code');
    }

    /**
     * Masing-masing gunungapi banyak peralatan monitoring
     *
     * @return void
     */
    public function alat()
    {
        return $this->hasMany('App\Alat','code_id','code');
    }
}
