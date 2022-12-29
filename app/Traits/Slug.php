<?php

namespace App\Traits;

use Illuminate\Support\Str;

/**
 * Generate Slug from name field
 */
trait Slug
{
    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $model->slug = Str::slug($model->name);
        });

        static::updating(function ($model) {
            $model->slug = Str::slug($model->name);
        });
    }
}
