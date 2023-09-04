<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class KrbGunungApi extends Model
{
    protected $casts = [
        'checked' => 'boolean',
    ];

    protected $with = [
        'user:nip,name',
        'gunungapi',
    ];

    protected $guarded = [
        'id',
    ];

    public function gunungapi()
    {
        return $this->belongsTo(Gadd::class, 'code', 'code');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'nip', 'nip');
    }

    public function penjelasans()
    {
        return $this->hasMany(KrbGunungApiPenjelasan::class, 'krb_code', 'krb_code');
    }

    public function indexMaps()
    {
        return $this->hasMany(KrbGunungApiIndexMap::class, 'krb_code', 'krb_code');
    }
}
