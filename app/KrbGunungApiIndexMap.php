<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class KrbGunungApiIndexMap extends Model
{
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

    public function krb()
    {
        return $this->belongsTo(KrbGunungApi::class, 'krb_code', 'krb_code');
    }
}
