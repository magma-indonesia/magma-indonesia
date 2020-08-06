<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GlossaryFile extends Model
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
        return asset('storage/glossary/'.$this->attributes['filename']);
    }

    /**
     * URL of thumbnail
     *
     * @return String
     */
    public function getThumbnailAttribute()
    {
        return asset('storage/glossary/thumbnails/'.$this->attributes['filename']);
    }
    
    /**
     * Undocumented function
     *
     * @return \App\Glossary
     */
    public function edukasi()
    {
        return $this->belongsTo('App\Glossary');
    }
}
