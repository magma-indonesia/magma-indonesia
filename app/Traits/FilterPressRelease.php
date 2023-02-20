<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;

trait FilterPressRelease
{
    public function scopeFilterByTag(Builder $query, string $slug)
    {
        return $query->whereHas('tags', function ($q) use ($slug) {
            $q->where('slug', $slug);
        });
    }

    public function scopeFilterByVolcanoCode(Builder $query, string $code)
    {
        return $query->whereHas('gunungApi', function ($q) use ($code) {
            $q->where('code', $code);
        });
    }
}