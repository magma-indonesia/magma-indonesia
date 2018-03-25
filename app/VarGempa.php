<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class VarGempa extends Model
{
    use SoftDeletes;

    protected $dates = ['deleted_at'];    

    protected $with = ['lts','apl','gug','apg','hbs','tre','tor','lof','hyb','vtb','vta','vlp','tel','trs','tej','dev','gtb','hrm','dpt','mtr'];

    protected $fillable = [

        'noticenumber_id',

    ];

    protected $hidden  = [

        'noticenumber_id',
        'created_at',
        'updated_at',
        'deleted_at'

    ];

    protected $guarded  = [
        
        'id'

    ];

    public static function jumlah()
    {
        $sum = 0;
        $sum += \App\EqApg::sum('jumlah');
        $sum += \App\EqApl::sum('jumlah');
        $sum += \App\EqDev::sum('jumlah');
        $sum += \App\EqDpt::sum('jumlah');
        $sum += \App\EqGtb::sum('jumlah');
        $sum += \App\EqGug::sum('jumlah');
        $sum += \App\EqHbs::sum('jumlah');
        $sum += \App\EqHrm::sum('jumlah');
        $sum += \App\EqHyb::sum('jumlah');
        $sum += \App\EqLof::sum('jumlah');
        $sum += \App\EqLts::sum('jumlah');
        $sum += \App\EqMtr::sum('jumlah');
        $sum += \App\EqTej::sum('jumlah');
        $sum += \App\EqTel::sum('jumlah');
        $sum += \App\EqTor::sum('jumlah');
        $sum += \App\EqTre::sum('jumlah');
        $sum += \App\EqTrs::sum('jumlah');
        $sum += \App\EqVlp::sum('jumlah');
        $sum += \App\EqVta::sum('jumlah');
        $sum += \App\EqVtb::sum('jumlah');
        
        return $sum;
    }

    /**     
     *   Menghitung total gempa keseluruhan
     *   yang tersimpan di magma
     */
    public function JumlahGempaGunungApi()
    {
        return $this->jumlah();
    }

    /**     
     *   Masing-masing Visual hanya dimiliki
     *   oleh 1 data VAR
     */
    public function var()
    {
        return $this->belongsTo('App\MagmaVars','noticenumber_id','noticenumber');
    }

    /**     
     *   Masing-masing Var hanya memiliki
     *   1 data Gempa Letusan
     */
    public function lts()
    {
        return $this->hasOne('App\EqLts');
    }

    /**     
     *   Masing-masing Var hanya memiliki
     *   1 data Hembusan
     */
    public function hbs()
    {
        return $this->hasOne('App\EqHbs');
    }

    /**     
     *   Masing-masing Var hanya memiliki
     *   1 data Tremor
     */
    public function tre()
    {
        return $this->hasOne('App\EqTre');
    }

    /**     
     *   Masing-masing Var hanya memiliki
     *   1 data Tornillo
     */
    public function tor()
    {
        return $this->hasOne('App\EqTor');
    }

    /**     
     *   Masing-masing Var hanya memiliki
     *   1 data Low Frequency
     */
    public function lof()
    {
        return $this->hasOne('App\EqLof');
    }

    /**     
     *   Masing-masing Var hanya memiliki
     *   1 data Guguran
     */
    public function gug()
    {
        return $this->hasOne('App\EqGug');
    }

    /**     
     *   Masing-masing Var hanya memiliki
     *   1 data Awan Panas Guguran
     */
    public function apg()
    {
        return $this->hasOne('App\EqApg');
    }

    /**     
     *   Masing-masing Var hanya memiliki
     *   1 data Awan Panas Letusan
     */
    public function apl()
    {
        return $this->hasOne('App\EqApl');
    }

    /**     
     *   Masing-masing Var hanya memiliki
     *   1 data Hybrid/Fase Banyak
     */
    public function hyb()
    {
        return $this->hasOne('App\EqHyb');
    }

    /**     
     *   Masing-masing Var hanya memiliki
     *   1 data Vulkanik Dangkal
     */
    public function vtb()
    {
        return $this->hasOne('App\EqVtb');
    }

    /**     
     *   Masing-masing Var hanya memiliki
     *   1 data Vulkanik Dalam
     */
    public function vta()
    {
        return $this->hasOne('App\EqVta');
    }

    /**     
     *   Masing-masing Var hanya memiliki
     *   1 data Very Long Period
     */
    public function vlp()
    {
        return $this->hasOne('App\EqVlp');
    }

    /**     
     *   Masing-masing Var hanya memiliki
     *   1 data Gempa Tektonik Jauh
     */
    public function tej()
    {
        return $this->hasOne('App\EqTej');
    }

    /**     
     *   Masing-masing Var hanya memiliki
     *   1 data Gempa Tektonik Lokal
     */
    public function tel()
    {
        return $this->hasOne('App\EqTel');
    }

    /**     
     *   Masing-masing Var hanya memiliki
     *   1 data Terasa
     */
    public function trs()
    {
        return $this->hasOne('App\EqTrs');
    }

    /**     
     *   Masing-masing Var hanya memiliki
     *   1 data Double Event
     */
    public function dev()
    {
        return $this->hasOne('App\EqDev');
    }

    /**     
     *   Masing-masing Var hanya memiliki
     *   1 data Getaran Banjir
     */
    public function gtb()
    {
        return $this->hasOne('App\EqGtb');
    }

    /**     
     *   Masing-masing Var hanya memiliki
     *   1 data Tremor Harmonik
     */
    public function hrm()
    {
        return $this->hasOne('App\EqHrm');
    }

    /**     
     *   Masing-masing Var hanya memiliki
     *   1 data Deep Tremor
     */
    public function dpt()
    {
        return $this->hasOne('App\EqDpt');
    }

    /**     
     *   Masing-masing Var hanya memiliki
     *   1 data Tremor Menerus/Microtremor
     */
    public function mtr()
    {
        return $this->hasOne('App\EqMtr');
    }

    /**     
     *   Masing-masing Var hanya memiliki
     *   1 data Gempa Tektonik Lokal
     */
    public function semua()
    {

        $this->setWith(array('tej','tel'));
        
    }
}
