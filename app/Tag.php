<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Slug;
use Illuminate\Database\Eloquent\Builder;

class Tag extends Model
{
    use Slug;

    protected $guarded = [
        'id',
    ];

    /**
     * Get all of the press releases that are assigned this tag.
     */
    public function press_releases()
    {
        return $this->morphedByMany(PressRelease::class, 'taggable');
    }

    public function scopeFilterBySlug(Builder $query, string $slug)
    {
        return $query->where('slug', $slug);
    }
}
