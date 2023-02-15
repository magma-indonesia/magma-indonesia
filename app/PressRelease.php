<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class PressRelease extends Model
{
    use Notifiable;

    protected $casts = [
        'gunung_api' => 'boolean',
        'gerakan_tanah' => 'boolean',
        'gempa_bumi' => 'boolean',
        'tsunami' => 'boolean',
    ];

    protected $guarded = [
        'id',
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
     * Undocumented function
     *
     * @return void
     */
    public function gunung_api()
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
