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
        return asset('storage/press-release/' . $this->attributes['filename']);
    }

    /**
     * URL of thumbnail
     *
     * @return String
     */
    public function getThumbnailAttribute()
    {
        return asset('storage/press-release/thumbnails/' . $this->attributes['filename']);
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
