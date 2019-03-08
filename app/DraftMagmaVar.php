<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DraftMagmaVar extends Model
{
    protected $primaryKey = 'noticenumber';

    public $incrementing = false;

    protected $guarded = ['id'];

    protected $with = [
        'user',
        'gunungapi'
    ];

    protected $casts = [
        'var' => 'array',
        'var_visual' => 'array',
        'var_klimatologi' => 'array',
        'var_gempa' => 'array',
    ];

    protected $fillable = [
        'noticenumber',
        'code_id',
        'nip_pelapor',
        'var',
        'var_visual',
        'var_klimatologi',
        'var_gempa',
        'var_saved',
        'var_visual_saved',
        'var_klimatologi_saved',
        'var_gempa_saved'
    ];

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
