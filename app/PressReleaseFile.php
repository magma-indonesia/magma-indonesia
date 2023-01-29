<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PressReleaseFile extends Model
{
    protected $guarded = [
        'id'
    ];

    protected $appends = [
        'url',
        'thumbnail'
    ];

    /**
     * URL of image
     *
     * @return String
     */
    public function getUrlAttribute()
    {
        return asset("storage/press-release/{$this->attributes['collection']}/{$this->attributes['name']}");
    }

    /**
     * URL of thumbnail
     *
     * @return String
     */
    public function getThumbnailAttribute()
    {
        if ($this->attributes['collection'] !== 'files') {
            return asset("storage/press-release/{$this->attributes['collection']}/thumbnails/{$this->attributes['name']}");
        }

        return null;
    }

    /**
     * Undocumented function
     *
     * @return \App\PressRelease
     */
    public function press_release()
    {
        return $this->belongsTo(PressRelease::class);
    }
}
