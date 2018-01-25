<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class VarAsap extends Model
{
    use SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $casts = [
        'wasap'         => 'array',
        'intasap'       => 'array',
        'tekasap'       => 'array'
    ];

    protected $fillable = [
        'var_visual_id',
        'tasap_min',
        'tasap_max',
        'wasap',
        'intasap',
        'tekasap'
    ];

    protected $guarded  = [
        'id'
    ];

    /**     
     *   Masing-masing parameter Asap bisa dimiliki
     *   oleh 1 data visual jika asapnya teramati
     */
    public function visual()
    {
        return $this->belongsTo('App\VarVisual','var_visual_id','id');
    }
}
