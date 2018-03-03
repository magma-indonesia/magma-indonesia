<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;

use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasRoles,SoftDeletes,Notifiable;

    protected $dates = ['deleted_at'];    

    /**
     * Route notifications for the Slack channel.
     *
     * @return string
     */
    public function routeNotificationForSlack()
    {
        return env('SLACK_WEBHOOK_LOGIN');
    }

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

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'id',
        'password', 
        'remember_token',
        'status',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    /**
     * Mengembalikan semua fungsi attribute Password
     * Dalam format bcrypt. Sehingga tidak perlu memberikan method/fungsi tambahan
     * untuk enkripsi password
     *
     * @var string
     */
    public function setPasswordAttribute($password)
    {   
        $this->attributes['password'] = bcrypt($password);
    }

    /**     
     *   Masing-masing user hanya memiliki 1 Foto
     */

    public function photo()
    {
        return $this->hasOne('App\UserPhoto');
    }

    /**     
     *   Masing-masing user bisa memiliki lebih
     *   dari 1 Press Release
     */
    public function press()
    {
        return $this->hasMany('App\Press');
    }

    /**     
     *   Masing-masing user bisa memiliki lebih
     *   dari 1 Vars
     */
    public function var()
    {
        return $this->hasMany('App\MagmaVar','nip','nip_pelapor');
    }
}
