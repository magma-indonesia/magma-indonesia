<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class VarVisual extends Model
{
    use SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $casts = [
        'visibility'    => 'array'
    ];

    protected $fillable = [
        'noticenumber_id',
        'visibility',
        'visual_asap',
        'visual_kawah'
    ];

    protected $hidden  = [
        'id',
        'noticenumber_id',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $guarded  = [
        'id'
    ];

    /**     
     *   Masing-masing Visual hanya dimiliki
     *   oleh 1 data VAR
     */
    public function var()
    {
        return $this->belongsTo('App\MagmaVars','noticenumber_id','noticenumber');
    }

    /**     
     *   Masing-masing Visual bisa memiliki parameter asap
     *   jika memang teramati
     */
    public function asap()
    {
        return $this->hasOne('App\VarAsap');
    }
}
