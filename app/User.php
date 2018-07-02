<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;

use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements JWTSubject
{
    use HasRoles,Notifiable;

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
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
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
        'password', 
        'remember_token',
        'status',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $guarded  = [
        'id'
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
     *   Masing-masing user hanya memiliki 1 Bidang
     */

    public function bidang()
    {
        return $this->hasOne('App\UserBidang','user_id','id');
    }

    /**     
     *   Masing-masing user hanya memiliki 1 Foto
     */

    public function photo()
    {
        return $this->hasOne('App\UserPhoto','user_id','id');
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
     *   Gunung Api
     *   Masing-masing user bisa memiliki lebih
     *   dari 1 Vars
     */
    public function var()
    {
        return $this->hasMany('App\MagmaVar','nip','nip_pelapor');
    }

    /**     
     *   Gerakan Tanah
     *   Masing-masing user bisa memiliki lebih
     *   dari 1 Crs
     */
    public function crs()
    {
        return $this->hasMany('App\SigertanCrsValidasi','nip','nip_id');
    }
}
