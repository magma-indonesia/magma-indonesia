<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PetaKrbGunungApi extends Model
{
    protected $fillable = [
        'code',
        'filename',
        'nip',
        'hit',
        'keterangan',
        'published'
    ];

    protected $casts = [
        'published' => 'boolean',
    ];

    public function gunungapi()
    {
        return $this->belongsTo('App\Gadd','code','code');
    }

    public function user()
    {
        return $this->belongsTo('App\User','nip','nip');
    }
}
