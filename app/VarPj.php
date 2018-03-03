<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class VarPj extends Model
{
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
