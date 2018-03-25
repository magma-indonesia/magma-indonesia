<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class VarPj extends Model
{
    use SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $fillable = [
        'noticenumber_id',
        'nip_id'
    ];

    protected $guarded  = [
        'id'
    ];

    protected $hidden  = [
        'id',
        'deleted_at',
    ];
}
