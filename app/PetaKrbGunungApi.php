<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Traits\ByteConverter;

class PetaKrbGunungApi extends Model
{
    use ByteConverter;

    protected $guarded = ['id'];

    protected $appends = [
        'url',
        'large_url',
        'medium_url',
        'thumbnail',
        'size_kb',
        'size_mb',
        'size_gb',
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

    /**
     * Size Image in KB
     *
     * @return String
     */
    public function getSizeKbAttribute()
    {
        return $this->getKiloByte($this->attributes['size']);
    }

    /**
     * Size Image in MB
     *
     * @return String
     */
    public function getSizeMbAttribute()
    {
        return $this->getMegaByte($this->attributes['size']);
    }

    /**
     * Size Image in GB
     *
     * @return String
     */
    public function getSizeGbAttribute()
    {
        return $this->getGigaByte($this->attributes['size']);
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
