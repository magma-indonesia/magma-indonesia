<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DataDasarSejarahLetusan extends Model
{
    protected $with = [
        'user:nip,name',
    ];

    protected $guarded = [
        'id',
    ];

    protected $casts = [
        'is_checked' => 'boolean',
    ];

    public function gunungapi()
    {
        return $this->belongsTo(Gadd::class, 'code', 'code');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'nip', 'nip');
    }
}
