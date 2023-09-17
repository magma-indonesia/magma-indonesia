<?php

namespace App\v1;

use App\DataDasarGeologi;
use App\DataDasarGunungApi;
use App\KrbGunungApi;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Gadd extends Model
{
    protected $connection = 'magma';

    public $timestamps = false;

    protected $primaryKey = 'no';

    protected $table = 'ga_dd';

    protected $guarded = ['no'];

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
        return $this->hasOne('App\v1\MagmaVar','ga_code','ga_code');
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

    public function rekomendasis()
    {
        return $this->hasManyThrough(
            'App\v1\MagmaVarListRekomendasi',
            'App\v1\MagmaVarRekomendasi',
            'ga_code',
            'magma_var_rekomendasi_id',
            'ga_code',
            'id'
        )->select('magma_var_list_rekomendasis.rekomendasi');
    }

    public function pos_pgas()
    {
        return $this->hasMany('App\v1\PosPga','code_id','ga_code');
    }

    public function krbGunungApi()
    {
        return $this->setConnection('mysql')
            ->hasOne(KrbGunungApi::class, 'code', 'ga_code')
            ->where('is_active', 1);
    }

    public function KrbGunungApiPenjelasans()
    {
        return $this->setConnection('mysql')
            ->hasMany(KrbGunungApiPenjelasan::class, 'code', 'code');
    }

    public function dataDasar()
    {
        return $this->setConnection('mysql')
            ->hasOne(DataDasarGunungApi::class, 'code', 'code');
    }

    public function dataDasarGeologi()
    {
        return $this->setConnection('mysql')
            ->hasOne(DataDasarGeologi::class, 'code', 'code');
    }

    public function petaKrbs()
    {
        return $this->setConnection('mysql')
            ->hasMany('App\PetaKrbGunungApi', 'code', 'code')
            ->orderBy('tahun');
    }

    public function dataDasarSejarahLetusan()
    {
        return $this->setConnection('mysql')
            ->hasMany(DataDasarSejarahLetusan::class, 'code', 'code');
    }
}
