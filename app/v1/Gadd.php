<?php

namespace App\v1;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Gadd extends Model
{
    protected $connection = 'magma';

    public $timestamps = false;

    protected $primaryKey = 'no';

    protected $table = 'ga_dd';

    protected $fillable = [
        'ga_code',
        'ga_nama_gapi',
        'ga_tzone',
        'ga_zonearea',
        'ga_kab_gapi',
        'ga_prov_gapi',
        'ga_koter_gapi',
        'ga_tipe_gapi',
        'ga_elev_gapi',
        'ga_lat_gapi',
        'ga_lon_gapi'
    ];

    public function normal()
    {
        return $this->hasOne('App\v1\MagmaVar','ga_code','ga_code')
                    ->select('ga_code','cu_status','var_data_date','var_rekom')
                    ->where('cu_status','Level I (Normal)')
                    ->orderBy('var_data_date','desc');
    }

    public function waspada()
    {
        return $this->hasOne('App\v1\MagmaVar','ga_code','ga_code')
                    ->select('ga_code','cu_status','var_data_date','var_rekom')
                    ->where('cu_status','Level II (Waspada)')
                    ->orderBy('var_data_date','desc');
    }

    public function siaga()
    {
        return $this->hasOne('App\v1\MagmaVar','ga_code','ga_code')
                    ->select('ga_code','cu_status','var_data_date','var_rekom')
                    ->where('cu_status','Level III (Siaga)')
                    ->orderBy('var_data_date','desc');
    }

    public function awas()
    {
        return $this->hasOne('App\v1\MagmaVar','ga_code','ga_code')
                    ->select('ga_code','cu_status','var_data_date','var_rekom')
                    ->where('cu_status','Level IV (Awas)')
                    ->orderBy('var_data_date','desc');
    }

    public function var()
    {
        return $this->hasOne('App\v1\MagmaVar','ga_code','ga_code')
                    ->orderBy('var_data_date','desc')
                    ->orderBy('periode','desc');
    }

    public function vona()
    {
        return $this->hasMany('App\v1\Vona','ga_code','ga_code')
                    ->orderBy('issued_time','desc');
    }

    public function one_vona()
    {
        return $this->hasMany('App\v1\Vona','ga_code','ga_code')
                ->orderBy('issued_time','desc')
                ->limit(1);
    }

    public function latest_vona()
    {
        return $this->hasOne('App\v1\Vona','ga_code','ga_code')
                    ->join(DB::raw('(SELECT ga_code, MAX(issued) as issued FROM ga_vona GROUP BY ga_code) latest_vona'), function($join) {
                        $join->on('ga_vona.issued','=','latest_vona.issued')
                            ->on('ga_vona.ga_code','=','latest_vona.ga_code');
                    });
    }

    public function history()
    {
        return $this->hasOne('App\v1\History','ga_code','ga_code');
    }

    public function users()
    {
        return $this->hasManyThrough(
            'App\v1\User',
            'App\v1\Kantor',
            'ga_code',
            'vg_nip',
            'ga_code',
            'vg_nip'
        )->select('vg_peg.vg_nip', 'vg_peg.vg_nama')
        ->whereNotIn('pga_pos.obscode', ['BTK', 'PAG', 'PSM', 'PSG', 'PVG', 'BGL'])
        ->where('vg_peg.status', 1);
    }

    public function pos_pgas()
    {
        return $this->hasMany('App\v1\PosPga','code_id','ga_code');
    }
    
}
