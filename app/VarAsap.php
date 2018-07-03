<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class VarAsap extends Model
{

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

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at',
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
        return $this->belongsTo('App\VarVisual');
    }
}
