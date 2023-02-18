<?php

namespace App;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Route;

class PressRelease extends Model
{
    use Notifiable;

    protected $casts = [
        'gunung_api' => 'boolean',
        'gerakan_tanah' => 'boolean',
        'gempa_bumi' => 'boolean',
        'tsunami' => 'boolean',
        'datetime' => 'datetime:Y-m-d H:i:s',
    ];

    protected $guarded = [
        'id',
    ];

    protected $appends = [
        'url',
        'cover_thumbnail_url',
    ];

    /**
     * Route notifications for the Telegram channel.
     *
     * @return int
     */
    public function routeNotificationForTelegram()
    {
        return config('services.telegram-bot-api.developer_channel');;
    }

    /**
     * URL of Press Release
     *
     * @return String
     */
    public function getUrlAttribute(): string
    {
        return route('press-release.show', ['id' => $this->attributes['id'], 'slug' => $this->attributes['slug']]);
    }

    /**
     * Undocumented function
     *
     * @return void
     */
    public function gunungApi()
    {
        return $this->belongsTo(Gadd::class, 'code', 'code');
    }

    /**
     * Get user
     *
     * @return void
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'nip', 'nip');
    }

    /**
     * Has Many files
     *
     * @return \App\PressReleaseFile
     */
    public function press_release_files()
    {
        return $this->hasMany(PressReleaseFile::class);
    }

    /**
     * Get thumbnail preview
     *
     * @return void
     */
    public function getCoverThumbnailUrlAttribute()
    {
        $thumbnailUrl = $this->press_release_files()->whereIn('collection', ['petas','gambars'])->first();

        return $thumbnailUrl ? $thumbnailUrl->thumbnail : null;
    }

    /**
     * Get all of the tags for the post.
     */
    public function tags()
    {
        return $this->morphToMany(Tag::class, 'taggable');
    }

    /**
     * Get all peta KRB for gunung api
     *
     * @return void
     */
    public function peta_krbs()
    {
        return $this->hasMany(PetaKrbGunungApi::class, 'code', 'code')->where('published', 1);
    }
}
