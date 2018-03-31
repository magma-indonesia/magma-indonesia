<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TesUserMgt extends Model
{
    protected $fillable = [
        'name', 
        'nip', 
        'email',
        'phone',
        'password',
        'status',
        'api_token',
        'remember_token'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 
        'remember_token',
        'status',
        'created_at',
        'updated_at'
    ];
}
