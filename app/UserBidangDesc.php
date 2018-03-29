<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserBidangDesc extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'user_bidang_desc_id'
    ];
}
