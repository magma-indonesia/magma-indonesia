<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MagmaVar extends Model
{
    use SoftDeletes;

    protected $dates = ['deleted_at','var_data_date'];

    protected $fillable = [
        'noticenumber',
        'var_issued',
        'code_id',
        'var_data_date',
        'periode',
        'var_perwkt',
        'obscode_id',
        'nip_pelapor',
        'nip_pj',
        'nip_verifikator',
    ];

    /**     
     *   Masing-masing Var hanya dimiliki
     *   oleh 1 User
     */
    public function user()
    {
        return $this->belongsTo('App\User','nip_pelapor','nip');
    }

    /**     
     *   Masing-masing Var hanya dimiliki
     *   oleh 1 Gunungapi
     */
    public function gunungapi()
    {
        return $this->belongsTo('App\Gadd','code_id','code');
    }

    /**     
     *   Masing-masing Var hanya memiliki
     *   1 data visual
     */
    public function visual()
    {
        return $this->hasOne('App\VarVisual','noticenumber','noticenumber_id');
    }

    /**     
     *   Masing-masing Var hanya memiliki
     *   1 data Var harian
     */
    public function varDaily()
    {
        return $this->hasOne('App\VarDaily','noticenumber','noticenumber_id');
    }
}
