<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Press extends Model
{
    use SoftDeletes;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title', 
        'body', 
        'user_id'
    ];

    /**
     * Press Release belongs to User
     *
     * @var array
     */
    public function user()
    {
        return $this->belongsTo('App\User');
    }

     /**
     * Press Release has many Photos
     *
     * @var array
     */
    public function photo()
    {
        return $this->hasMany('App\PressPhoto');
    }
}
