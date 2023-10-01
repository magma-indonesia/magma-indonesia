<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PeralatanPemantauan extends Model
{
    protected $guarded = [
        'id',
    ];

    /**
     * Merubah bidang menjadi textual
     *
     * @return string
     */
    public function getBidangAttribute(): string
    {
        switch ($this->attributes['bidang']) {
            case 'ga':
                return 'Gunung Api';
            case 'gt':
                return 'Gerakan Tanah';
            case 'gb':
                return 'Gempa Bumi';
            case 'ts':
                return 'Lainnya';
            default:
                return 'Lainnya';
        }
    }

    public function gunungapi()
    {
        return $this->belongsTo(Gadd::class, 'code', 'code');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'nip', 'nip');
    }
}
