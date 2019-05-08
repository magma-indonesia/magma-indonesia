<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class Stakeholder extends Authenticatable implements JWTSubject
{
    use Notifiable;

    protected $casts = [
        'status' => 'boolean'
    ];

    protected $dates = [
        'expired_at',
    ];    

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'app_name', 
        'uuid', 
        'organisasi',
        'api_type',
        'secret_key',
        'status',
        'kontak_nama',
        'kontak_phone',
        'kontak_nama',
        'status',
        'expired_at'
    ];

    protected $guarded  = [
        'id'
    ];

    protected $hidden  = [

    ];

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
        return [
            'source' => 'MAGMA Indonesia',
            'api_version' => 'v1'
        ];
    }
}
