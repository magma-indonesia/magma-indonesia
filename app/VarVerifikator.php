<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class VarVerifikator extends Model
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
