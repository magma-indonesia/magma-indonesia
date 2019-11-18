<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ResumeHarian extends Model
{
    protected $fillable = [
        'tanggal',
        'resume',
        'truncated'
    ];

    protected $dates = [
        'tanggal'
    ];

    protected $appends = [
        'resume_original',
        'truncated_original',
    ];

    public function getResumeOriginalAttribute()
    {
        return str_replace('*','',$this->resume);
    }

    public function getTruncatedOriginalAttribute()
    {
        return str_replace('*','',$this->truncated);
    }
}
