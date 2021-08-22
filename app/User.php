<?php

namespace App;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;

use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements JWTSubject
{
    use HasRoles,Notifiable;

    protected $dates = [
        'created_at',
        'updated_at',
        'last_login_at'
    ];

    protected $casts = [
        'status' => 'boolean'
    ];

    /**
     * Route notifications for the Slack channel.
     *
     * @return string
     */
    public function routeNotificationForSlack()
    {
        return env('SLACK_WEBHOOK_LOGIN');
    }

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [
            'source' => 'MAGMA Indonesia',
            'api_version' => 'v1'
        ];
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'nip',
        'email',
        'phone',
        'password',
        'status',
        'api_token',
        'remember_token',
        'last_login_at',
        'last_login_ip'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'status',
    ];

    protected $guarded  = [
        'id'
    ];

    /**
     * Mengembalikan semua fungsi attribute Password
     * Dalam format bcrypt. Sehingga tidak perlu memberikan method/fungsi tambahan
     * untuk enkripsi password
     *
     * @var string
     */
    public function setPasswordAttribute($password)
    {
        $this->attributes['password'] = bcrypt($password);
    }

    /**
     *   Masing-masing user hanya memiliki 1 Bidang
     */

    public function bidang()
    {
        return $this->hasOne('App\UserAdministratif','user_id','id');
    }

    public function administrasi()
    {
        return $this->hasOne('App\UserAdministratif','user_id','id');
    }

    /**
     *   Masing-masing user hanya memiliki 1 Foto
     */

    public function photo()
    {
        return $this->hasOne('App\UserPhoto','user_id','id');
    }

    /**
     *   Masing-masing user bisa memiliki lebih
     *   dari 1 Press Release
     */
    public function press()
    {
        return $this->hasMany('App\Press');
    }

    /**
     *   Gunung Api
     *   Masing-masing user bisa memiliki lebih
     *   dari 1 Vars
     */
    public function var()
    {
        return $this->hasMany('App\MagmaVar','nip_pelapor','nip');
    }

    /**
     *   Gerakan Tanah
     *   Masing-masing user bisa memiliki lebih
     *   dari 1 Crs
     */
    public function crs()
    {
        return $this->hasMany('App\SigertanCrsValidasi','nip_id','nip');
    }

    /**
     * Setiap user bisa memiliki lebih dari 1 absensi
     *
     * @return HasMany;
     */
    public function absensi()
    {
        return $this->hasMany('App\Absensi','nip_id','nip');
    }

    /**
     * Setiap user login lebih dari 1 kali
     *
     * @return HasMany;
     */
    public function statistik_logins()
    {
        return $this->hasMany('App\StatistikLogin','nip','nip');
    }

    /**
     * Setiap user bisa memiliki lebih dari 1 absensi
     *
     * @return HasOne;
     */
    public function latest_absensi()
    {
        return $this->hasOne('App\Absensi','nip_id','nip');
    }

    /**
     * Setiap user bisa terdaftar lebih dari 1 kegiatan
     *
     * @return HasMany;
     */
    public function anggota_kegiatan()
    {
        return $this->hasMany('App\MGA\AnggotaKegiatan','nip_anggota','nip');
    }

    /**
     * Jumlah Dinas Kegiatan
     *
     * @return int
     */
    public function getJumlahDinasAttribute()
    {
        return $this->anggota_kegiatan()->get()->sum('jumlah_hari');
    }

    /**
     * Jumlah Uang Lapangan yang diterima
     *
     * @return int
     */
    public function getJumlahRealisasiAttribute()
    {
        $anggota = $this->anggota_kegiatan()->get();
        $uang_harian = $anggota->sum('uang_harian_total');
        $uang_penginapan_tigapuluh = $anggota->sum('penginapan_tigapuluh_total');

        return $uang_harian+$uang_penginapan_tigapuluh;
    }

    public function events()
    {
        return $this->hasMany('App\EventCatalog', 'scnl', 'scnl');
    }
}
