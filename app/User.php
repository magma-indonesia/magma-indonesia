<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;

use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasRoles,SoftDeletes,Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
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

    protected $dates = ['deleted_at'];
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function setPasswordAttribute($password)
    {   
        $this->attributes['password'] = bcrypt($password);
    }

    /*     
     *   Masing-masing user hanya memiliki 1 Foto
     */

    public function photo()
    {
        return $this->hasOne('App\UserPhoto');
    }

    /*     
     *   Masing-masing user bisa memiliki lebih
     *   dari 1 Press Release
     */
    public function press()
    {
        return $this->hasMany('App\Press');
    }
}
