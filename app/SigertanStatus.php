<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SigertanStatus extends Model
{
    protected $with = ['user:nip,name'];

    protected $fillable = [
        'noticenumber_id',
        'nip_penerbit',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo('App\User','nip_penerbit','nip');
    }
}
