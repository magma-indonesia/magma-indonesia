<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class KrbGunungApiPenjelasan extends Model
{
    protected $with = [
        'user:nip,name',
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


    public function krbGunungApi()
    {
        return $this->belongsTo(KrbGunungApi::class, 'krb_code', 'krb_code');
    }
}
