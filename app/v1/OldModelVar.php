<?php

namespace App\v1;

use Illuminate\Database\Eloquent\Model;

class OldModelVar extends Model
{
    //region HELPER

    protected $collection, $mapping, $maximum, $minimum;

    /**
     * Intersect dua data array
     * dengan return collection
     *
     * @param array $a
     * @param array $b
     * @return object
     */
    protected function intersectVar(array $a,array $b) : object
    {

        $inter = array_intersect($a,$b);

        if ($inter)
        {
            $inter      = collect($inter);
            $inter      = $inter->values();

            return $inter;
        }

        return collect($inter)->values();
    }

    /**
     * Convert arah ke object collection
     *
     * @param string $value
     * @return object
     */
    protected function arah(string $value) : object
    {
        $s_arah = [
            'Utara','Timur Laut','Timur',
            'Tenggara','Selatan','Barat Daya',
            'Barat','Barat Laut'
        ];

        $data = str_replace(', ',',',$value);

        $arah = explode(',',$data);

        return $this->intersectVar($s_arah,$arah);
    }
    
    /**
     * Menghapus nilai null dari string ke object collection
     *
     * @param string $value
     * @return object
     */
    protected function rejectNull(string $value)
    {
        $value = explode('#',$value);
        $collection = collect($value)->reject(function ($value, $key) {
            return $value == 'null' || $value == '0';
        });
        $this->collection = $collection;
        return $this;
    }

    /**
     * Mapping string to integer
     *
     * @param string $value
     * @return object
     */
    protected function toInt()
    {
        $collection = $this->collection->map(function ($item,$key) {
            return floatval($item);
        });

        $this->mapping = $collection;
        $this->maximum = $collection->max();
        $this->minimum = $collection->min();
        return $this;
    }

    /**
     * Mendapatkan maximum value dari collection
     *
     * @return void
     */
    protected function getMax()
    {
        return $this->maximum;
    }

    /**
     * Mendapatkan maximum value dari collection
     *
     * @return void
     */
    protected function getMin()
    {
        return $this->minimum;
    }

    /**
     * Merubah null menjadi 0.0
     *
     * @return void
     */
    protected function getZero()
    {
        if ($this->minimum == null) {
            $this->minimum = 0.0;
        }

        if ($this->maximum == null) {
            $this->maximum = 0.0;
        }

        return $this;
    }

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

    protected function warnaAsap(string $value)
    {
        $s_wasap = ['Putih','Kelabu','Cokelat','Hitam'];

        if ($value == '-' || empty($value)) {
            return null;
        }

        $wasap = str_replace(', ', ',', $value);
        $wasap = str_replace('#',',',$wasap);

        $wasap = str_replace('Coklat','Cokelat',$wasap);
        $wasap = explode(',',$wasap);

        return $this->intersectVar($s_wasap,$wasap);
    }

    protected function skala(string $value)
    {
        $s_skala = ['I','II','III','IV','V','VI','VII'];

        $data = str_replace(', ',',',$value);

        $skala = explode(',',$data);

        return $this->intersectVar($s_skala,$skala);
    }
    //endregion

    //region MAGMA-VAR
    /**
     * Mengambil value var_image terakhir
     * Mengubahnya menjadi array dan mengambil nilai dari array terakhir (untuk merapi)
     *
     * @param string $value
     * @return string
     */
    public function getVarImageAttribute(string $value) : string
    {
        return $this->rejectNull($value)
            ->collection->last();
    }

    /**
     * Mengambil value 
     *
     * @param string $value
     * @return string
     */
    public function getVarImageCreateAttribute(string $value) : string
    {
        return $this->rejectNull($value)
            ->collection->last();
    }

    /**
     * Memastikan format tanggal pada variable var_issued
     *
     * @param string $value
     * @return string
     */
    public function getVarIssuedAttribute(string $value) : string
    {
        $var_issued = str_replace('/','-',$value);
        return date('Y-m-d H:i:s', strtotime($var_issued));
    }

    /**
     * Merubah level status gunung api menjadi integer
     *
     * @param string $value
     * @return integer
     */
    public function getCuStatusAttribute(string $value) :int
    {
        return $this->getStatus($value);
    }

    /**
     * Merubah level status gunung api menjadi integer
     *
     * @param string $value
     * @return integer
     */
    public function getPreStatusAttribute(string $value) : int
    {
        return $this->getStatus($value);
    }

    /**
     * Merubah periode waktu laporan dari string menjadi integer
     *
     * @param string $value
     * @return integer
     */
    public function getVarPerwktAttribute(string $value)
    {
        switch ($value) {
            case '24 Jam':
                return '24';
            case '6 Jam':
                return '6';
            default:
                return '12';
        }
    }

    /**
     * Mendapatkan nilai maksimum dari array string (untuk merapi)
     * dan nilai integer (untuk non merapi)
     *
     * @param string $value
     * @return integer
     */
    public function getVarCurahHujanAttribute(string $value) : float
    {
        return $this->rejectNull($value)
            ->toInt()->getZero()->getMax();
    }

    /**
     * Mendapatkan nilai maksimum dari array string (untuk merapi)
     * dan nilai integer (untuk non merapi)
     *
     * @param string $value
     * @return integer
     */
    public function getVarSuhuminAttribute(string $value)
    {
        $value = $this->rejectNull($value)->toInt()->getZero()->getMin();
        $value = $value>99 ? $value/10 : $value;
        $value = $value>40 ? 20 : $value;

        return $value;
    }

    /**
     * Mendapatkan nilai maksimum dari array string (untuk merapi)
     * dan nilai integer (untuk non merapi)
     *
     * @param string $value
     * @return integer
     */
    public function getVarSuhumaxAttribute(string $value) : float
    {
        $value = $this->rejectNull($value)->toInt()->getZero()->getMax();
        $value = $value>99 ? $value/10 : $value;
        $value = $value>40 ? 40 : $value;

        return $value;
    }

    /**
     * Mendapatkan nilai maksimum dari array string (untuk merapi)
     * dan nilai integer (untuk non merapi)
     *
     * @param string $value
     * @return integer
     */
    public function getVarKelembabanminAttribute(string $value) : float
    {
        return $this->rejectNull($value)
            ->toInt()->getZero()->getMin();
    }

    /**
     * Mendapatkan nilai maksimum dari array string (untuk merapi)
     * dan nilai integer (untuk non merapi)
     *
     * @param string $value
     * @return integer
     */
    public function getVarKelembabanmaxAttribute(string $value) : float
    {
        return $this->rejectNull($value)
            ->toInt()->getZero()->getMax();
    }

    /**
     * Mendapatkan nilai maksimum dari array string (untuk merapi)
     * dan nilai integer (untuk non merapi)
     *
     * @param string $value
     * @return integer
     */
    public function getVarTekananminAttribute(string $value)
    {
        $value = $this->rejectNull($value)->toInt()->getZero()->getMin();
        $value = $value>999 ? $value/10 : $value;
        $value = $value>999 ? 760 : $value;

        return $value;
    }

    /**
     * Mendapatkan nilai maksimum dari array string (untuk merapi)
     * dan nilai integer (untuk non merapi)
     *
     * @param string $value
     * @return integer
     */
    public function getVarTekananmaxAttribute(string $value)
    {
        $value = $this->rejectNull($value)->toInt()->getZero()->getMax();
        $value = $value>999 ? $value/10 : $value;
        $value = $value>999 ? 945 : $value;

        return $value;
    }

    /**
     * Mendapatkan visual gunung api
     *
     * @param string $value
     * @return object
     */
    public function getVarVisibilityAttribute(string $value) : object
    {
        $value = str_replace('Kabut-I','Kabut 0-I',$value);
        $value = str_replace('Kabut-II','Kabut 0-II',$value);
        $value = str_replace('Kabut-III','Kabut 0-III',$value);

        $value = str_replace(', ',',',$value);

        $s_visibility = ['Jelas','Kabut 0-I','Kabut 0-II','Kabut 0-III'];

        $visibility = explode(',',$value);

        return $this->intersectVar($s_visibility,$visibility);
    }

    /**
     * Merubah string dari cuava menjadi object collection
     *
     * @param string $value
     * @return object
     */
    public function getVarCuacaAttribute(string $value) : object
    {
        $s_cuaca = ['Cerah','Berawan','Mendung','Hujan','Badai'];
        $cuaca = str_replace(', ',',',$value);

        $cuaca = empty($cuaca) ? 'Cerah' : $cuaca;
        $cuaca = explode(',',$cuaca);

        return $this->intersectVar($s_cuaca,$cuaca);
    }

    /**
     * Merubah string kecepatan angin menjadi object collection
     *
     * @param $string $value
     * @return object
     */
    public function getVarKecanginAttribute(string $value) : object
    {
        $s_kec = ['Tenang','Lemah','Sedang','Kencang'];
        $data = str_replace(', ',',',$value);
        $data = str_replace('#',',',$data);
        $kec = explode(',',$data);

        return $this->intersectVar($s_kec,$kec);
    }

    /**
     * Merubah string arah angin menjadi object collection
     *
     * @param string $value
     * @return object
     */
    public function getVarAranginAttribute(string $value) : object
    {
        $data = str_replace('#',',',$value);
        return $this->arah($data);
    }

    /**
     * Mendapatkan visual asap, Nihil, Teramati atau Tidak Teramati
     *
     * @param string $value
     * @return string
     */
    public function getVarAsapAttribute(string $value) : string
    {
        $asap = $value;
        $tmin = $this->attribute['var_tasap_min'];
        $tmax = $this->attribute['var_tasap'];

        $asap = str_replace(', ',',',$asap);

        $s_asap = ['Nihil','Teramati','Tidak Teramati'];
        
        if (empty($asap))
        {

            if (($tmin>0) || ($tmax>0))
            {

                $asap   = 'Teramati';
                return $asap;

            }

            $asap = 'Tidak Teramati';
            return $asap;

        }

        $asap = explode(',',$asap);
        $asap = $this->intersectVar($s_asap,$asap);

        return $asap[0];
    }

    /**
     * Merubah nilai Tinggi Asap Min dari string menjadi float
     *
     * @param string $value
     * @return float
     */
    public function getVarTasapMinAttribute(string $value) : float
    {
        return $this->rejectNull($value)
            ->toInt()->getZero()->getMax();
    }

    /**
     * Merubah nilai Tinggi Asap Max dari string menjadi float
     *
     * @param string $value
     * @return float
     */
    public function getVarTasapAttribute(string $value) : float
    {
        return $this->rejectNull($value)
            ->toInt()->getZero()->getMax();
    }

    /**
     * Mendapatkan data visual kawah
     *
     * @param string $value
     * @return string
     */
    public function getVarViskawahAttribute(string $value) : string
    {
        if (strlen($value) > 5)
        {
            $visuals = explode('#', $value);
            $data = '';

            foreach ($visuals as $visual) {
                $data = strlen($visual) > 5 ? $data.' '.$visual : $data;
            }

            $data = empty($data) ? 'Nihil' : $data;

            return $data;
        }

        return 'Nihil';
    }

    /**
     * Merubah nilai string dari Warna Asap menjadi Array
     *
     * @param string $value
     * @return void
     */
    public function getVarWasapAttribute(string $value)
    {
        return $this->warnaAsap($value);
    }

    /**
     * Merubah nilai string dari Intensitas Asap menjadi Array
     *
     * @param string $value
     * @return void
     */
    public function getVarIntasapAttribute(string $value)
    {
        $s_intasap = ['Tipis','Sedang','Tebal'];

        if ($value == '-' || empty($value)) {
            return null;
        }

        $intasap = str_replace(', ',',',$value);
        $intasap = str_replace('#',',',$intasap);

        if ($intasap == '-') {
            return null;
        }

        if (empty($intasap)) {
            $intasap = ['Tipis'];
            return $intasap;
        }

        $intasap = explode(',',$intasap);
        return $this->intersectVar($s_intasap,$intasap);
    }

    /**
     * Merubah nilai string dari Tekanan Asap menjadi Array
     *
     * @param string $value
     * @return void
     */
    public function getVarTekasapAttribute(string $value)
    {
        $s_tekasap = ['Lemah','Sedang','Kuat'];

        if ($value == '-' || empty($value)) {
            return null;
        }

        $tekasap = str_replace(', ',',',$value);

        if (empty($tekasap)) {
            $tekasap = ['Lemah'];
            return $this->intersectVar($s_tekasap,$tekasap);
        }

        $tekasap = explode(',',$tekasap);
        return $this->intersectVar($s_tekasap,$tekasap);
    }

    /**
     * Merubah arah luncuran dari string menjadi array
     *
     * @param string $value
     * @return void
     */
    public function getVarGugAlunAttribute(string $value)
    {
        $data = str_replace('#',',',$value);
        return $this->arah($data)->isEmpty() ? null : $this->arah($data) ;
    }

    /**
     * Merubah arah luncuran dari string menjadi array
     *
     * @param string $value
     * @return void
     */
    public function getVarApgAlunAttribute(string $value)
    {
        $data = str_replace('#',',',$value);
        return $this->arah($data)->isEmpty() ? null : $this->arah($data) ;
    }

    /**
     * Merubah arah luncuran dari string menjadi array
     *
     * @param string $value
     * @return void
     */
    public function getVarAplAlunAttribute(string $value)
    {
        $data = str_replace('#',',',$value);
        return $this->arah($data)->isEmpty() ? null : $this->arah($data) ;
    }

    /**
     * Merubah warna abu letusan dari string menjadi array
     *
     * @param string $value
     * @return void
     */
    public function getVarLtsWasapAttribute(string $value)
    {
        return $this->warnaAsap($value);
    }

    /**
     * Skala untuk gempa terasa
     *
     * @param [type] $value
     * @return void
     */
    public function getVarTrsSkalaminAttribute($value)
    {
        $skala = $value.','.$this->attributes['var_trs_skalamax'];
        return $this->skala($skala);
    }

    /**
     * Normalize nilai amplitudo
     *
     * @param [type] $value
     * @return void
     */
    public function getVarVtaAminAttribute($value)
    {
        $value = $value > 999 ? $value/10 : $value;
        $value = $value > 120 ? 15 : $value;

        return $value;
    }

    /**
     * Normalize nilai amplitudo
     *
     * @param [type] $value
     * @return void
     */
    public function getVarVtaAmaxAttribute($value)
    {
        $value = $value > 999 ? $value/10 : $value;
        $value = $value > 120 ? 120 : $value;

        return $value;
    }

    public function getVarVtaSpminAttribute($value)
    {
        $value = $value > 50 ? 1.5 : $value;

        return $value;
    }

    public function getVarVtaSpmaxAttribute($value)
    {
        $value = $value > 50 ? 3 : $value;

        return $value;
    }

    public function getVarTejAminAttribute($value)
    {
        $value = $value > 999 ? $value/100 : $value;
        $value = $value > 99 ? $value/10 : $value;

        return $value;
    }

    public function getVarTejAmaxAttribute($value)
    {
        $value = $value > 999 ? $value/100 : $value;
        $value = $value > 99 ? $value/10 : $value;

        return $value;
    }

    public function getVarTelAmaxAttribute($value)
    {
        $value = $value > 999 ? $this->attributes['var_tel_amin'] : $value;

        return $value;
    }

    public function getVarTelSpmaxAttribute($value)
    {
        $value = $value > 50 ? 3 : $value;

        return $value;
    }

    public function getVarTejSpmaxAttribute($value)
    {
        $value = $value > 50 ? 3 : $value;
        $value = $value < $this->attributes['var_tej_spmin'] ? $this->attributes['var_tej_spmin'] : $value;

        return $value;
    }
    
    public function getVarVtaDmaxAttribute($value)
    {
        $value = $value > 999 ? $value/100 : $value;
        $value = $value > 99 ? $value/10 : $value;

        return $value;
    }
    //endregion

}
