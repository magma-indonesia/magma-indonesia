<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EdukasiFile extends Model
{
    protected $guarded = ['id'];

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
        return asset('storage/edukasi/'.$this->attributes['filename']);
    }

    /**
     * URL of thumbnail
     *
     * @return String
     */
    public function getThumbnailAttribute()
    {
        return asset('storage/edukasi/thumbnails/'.$this->attributes['filename']);
    }

    /**
     * Undocumented function
     *
     * @return void
     */
    public function edukasi()
    {
        return $this->belongsTo('App\Edukasi');
    }
}
