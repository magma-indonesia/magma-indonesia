<?php

namespace App;

use App\Traits\ByteConverter;
use Illuminate\Database\Eloquent\Model;

class MagmaVenFile extends Model
{
    use ByteConverter;

    protected $guarded = [
        'id'
    ];

    protected $appends = [
        'url',
        'thumbnail',
        'size_kb',
        'size_mb',
    ];

    /**
     * URL of image
     *
     * @return String
     */
    public function getUrlAttribute()
    {
        return asset("storage/ven/{$this->attributes['collection']}/{$this->attributes['name']}");
    }

    /**
     * URL of thumbnail
     *
     * @return String
     */
    public function getThumbnailAttribute()
    {
        if ($this->attributes['collection'] !== 'files') {
            return asset("storage/ven/{$this->attributes['collection']}/thumbnails/{$this->attributes['name']}");
        }

        return null;
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
     * Undocumented function
     *
     * @return \App\MagmaVen
     */
    public function magma_ven()
    {
        return $this->belongsTo(MagmaVen::class);
    }
}
