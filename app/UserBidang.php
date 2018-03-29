<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserBidang extends Model
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

    protected $guarded  = [
        'id'
    ];

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function deskriptif()
    {
        return $this->belongsTo('App\UserBidangDesc','user_bidang_desc_id','id');
    }
}
