<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MagmaVar extends Model
{

    public $timestamps = false;

    protected $casts = [
        'var_data_date' => 'date:Y-m-d',
    ];

    protected $dates = ['deleted_at','var_data_date'];

    protected $fillable = [
        'noticenumber',
        'code_id',
        'var_data_date',
        'periode',
        'var_perwkt',
        'obscode_id',
        'status',
        'nip_pelapor',
        'created_at'
    ];

    protected $appends = [
        'status_deskripsi'
    ];

    public function getStatusDeskripsiAttribute()
    {
        switch ($this->attributes['status']) {
            case 1:
                return 'Level I (Normal)';
            case 2:
                return 'Level II (Waspada)';
            case 3:
                return 'Level III (Siaga)';
            default:
                return 'Level IV (Awas)';
        }
    }

    /**     
     *   Masing-masing Var hanya dimiliki
     *   oleh 1 User
     * 
     *   @return \App\User 
     * 
     */
    public function user()
    {
        return $this->belongsTo('App\User','nip_pelapor','nip');
    }

    /**     
     *   Masing-masing Var bisa memiliki
     *   lebih dari 1 Verifikator PJ
     * 
     *   @return \App\VarVerifikator
     */
    public function pj()
    {
        return $this->hasMany('App\VarPj','noticenumber_id','noticenumber');
    }

    /**     
     *   Masing-masing Var bisa memiliki
     *   lebih dari 1 Verifikator
     * 
     *   @return \App\VarVerifikator
     */
    public function verifikator()
    {
        return $this->hasOne('App\VarVerifikator','noticenumber_id','noticenumber');
    }

    /**     
     *   Masing-masing Var hanya memiliki
     *   oleh 1 Pos
     * 
     *   @return \App\PosPga 
     * 
     */
    public function pos()
    {
        return $this->belongsTo('App\PosPga','obscode_id','obscode');
    }

    /**     
     *   Masing-masing Var hanya dimiliki
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
     *   Masing-masing Var hanya memiliki
     *   1 data visual
     *   @return \App\VarVisual
     */
    public function visual()
    {
        return $this->hasOne('App\VarVisual','noticenumber_id','noticenumber');
    }

    /**     
     *   Masing-masing Var hanya memiliki
     *   1 data klimatologi
     *   @return \App\VarKlimatologi
     */
    public function klimatologi()
    {
        return $this->hasOne('App\VarKlimatologi','noticenumber_id','noticenumber');
    }

    /**     
     *   Masing-masing Var hanya memiliki
     *   1 data Var harian
     */
    public function varDaily()
    {
        return $this->hasOne('App\VarDaily','noticenumber_id','noticenumber');
    }

    /**     
     *   Masing-masing Var hanya memiliki
     *   1 data Var harian
     */
    public function gempa()
    {
        return $this->hasOne('App\VarGempa','noticenumber_id','noticenumber');
    }

}