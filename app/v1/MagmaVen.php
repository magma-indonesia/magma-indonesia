<?php

namespace App\v1;

use Illuminate\Database\Eloquent\Model;

class MagmaVen extends Model
{
    protected $connection = 'magma';

    protected $table = 'magma_erupt';

    protected $primaryKey = 'erupt_id';

    protected $casts = [
        'is_published' => 'boolean',
        'is_blasted' => 'boolean',
        'erupt_tsp' => 'datetime:Y-m-d H:i:s'
    ];

    public $timestamps = false;

    protected $guarded = [
        'erupt_id',
    ];

    /**
     * Merubah status dari string menjadi integer
     *
     * @param string $value
     * @return integer
     */
    protected function getStatus(string $value) : int
    {
        switch ($value) {
            case 'Level IV (Awas)':
                return 4;
            case 'Level III (Siaga)':
                return 3;
            case 'Level II (Waspada)':
                return 2;
            default:
                return 1;
        }
    }

    /**
     * Merubah warna abu dari string menjadi array
     *
     * @param string $value
     * @return array
     */
    public function getEruptWrnAttribute(string $value)
    {
        return $value == '-' ? null : explode(', ',$value);
    }

    /**
     * Merubah intensitas abu dari string menjadi array
     *
     * @param string $value
     * @return array
     */
    public function getEruptIntAttribute(string $value)
    {
        return $value == '-' ? null : explode(', ',$value);
    }

    /**
     * Merubah arah asap dari string menjadi array
     *
     * @param string $value
     * @return array
     */
    public function getEruptArhAttribute(string $value)
    {
        return $value == '-' ? null : explode(', ',$value);
    }

    /**
     * Foto letusan
     *
     * @param string $value
     * @return array
     */
    public function getEruptPhtAttribute(string $value)
    {
        return $value == '-' ? null : $value;
    }

    /**
     * Merubah status string menjadi int
     *
     * @param string $value
     * @return array
     */
    public function getEruptStaAttribute(string $value)
    {
        return $this->getStatus($value);
    }

    /**
     * Merubah nilai empty keterangan menjadi null
     *
     * @param string $value
     * @return array
     */
    public function getEruptKetAttribute(string $value)
    {
        return $value == '-' ? null : $value;
    }

    public function gunungapi()
    {
        return $this->belongsTo('App\v1\Gadd','ga_code','ga_code');
    }

    public function user()
    {
        return $this->belongsTo('App\v1\User','erupt_usr','vg_nip');
    }
}
