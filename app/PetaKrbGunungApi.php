<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PetaKrbGunungApi extends Model
{
    protected $guarded = ['id'];

    protected $appends = [
        'url',
        'large_url',
        'medium_url',
        'thumbnail'
    ];

    protected $casts = [
        'published' => 'boolean',
    ];

    /**
     * URL of image
     *
     * @return String
     */
    public function getUrlAttribute()
    {
        return asset('storage/krb-gunungapi/'.$this->attributes['filename']);
    }

    /**
     * URL of image
     *
     * @return String
     */
    public function getLargeUrlAttribute()
    {
        return asset('storage/krb-gunungapi/large/'.$this->attributes['filename']);
    }

    /**
     * URL of image
     *
     * @return String
     */
    public function getMediumUrlAttribute()
    {
        return asset('storage/krb-gunungapi/medium/'.$this->attributes['filename']);
    }

    /**
     * URL of thumbnail
     *
     * @return String
     */
    public function getThumbnailAttribute()
    {
        return asset('storage/krb-gunungapi/thumbnails/'.$this->attributes['filename']);
    }

    public function gunungapi()
    {
        return $this->belongsTo('App\Gadd','code','code');
    }

    public function user()
    {
        return $this->belongsTo('App\User','nip','nip');
    }
}
