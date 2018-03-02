<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserPhoto extends Model
{
    protected $table    = 'users_photo';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 
        'filename'
    ];

    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
