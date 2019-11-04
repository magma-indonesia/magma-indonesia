<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class VarRekomendasi extends Model
{
    protected $fillable = [
        'code_id',
        'status',
        'rekomendasi',
    ];

    protected $guard = [
        'id'
    ];

    protected $appends = [
        'status_text'
    ];

    public function getStatusTextAttribute()
    {
        switch ($this->status) {
            case '1':
                return 'Level I (Normal)';
            case '2':
                return 'Level II (Waspada)';
            case '3':
                return 'Level III (Siaga)';
            default:
                return 'Level IV (Awas)';
        }
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
}
