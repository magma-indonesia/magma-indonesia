<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DraftMagmaVar extends Model
{
    protected $primaryKey = 'noticenumber';

    public $incrementing = false;

    protected $guarded = ['id'];

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
}