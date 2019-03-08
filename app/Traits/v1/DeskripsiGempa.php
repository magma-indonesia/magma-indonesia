<?php

namespace App\Traits\v1;

use Illuminate\Http\Request;

trait DeskripsiGempa
{
    protected $deskripsi_gempa = [];

    protected $var;

    protected $jumlah;

    protected $amplitudo = [];

    protected $amplitudo_dominan;

    protected $sp = [];

    protected $durasi = [];

    protected $jarak_luncuran = [];

    protected $arah_luncuran;

    protected $skala = [];

    protected $codes = [
        'lts' => 'Letusan/Erupsi',
        'apl' => 'Awan Panas Letusan',
        'gug' => 'Guguran',
        'apg' => 'Awan Panas Guguran',
        'hbs' => 'Hembusan',
        'tre' => 'Tremor Non-Harmonik',
        'tor' => 'Tornillo',
        'lof' => 'Low Frequency',
        'hyb' => 'Hybrid/Fase Banyak',
        'vtb' => 'Vulkanik Dangkal',
        'vta' => 'Vulkanik Dalam',
        'vlp' => 'Very Long Period',
        'tel' => 'Tektonik Lokal',
        'trs' => 'Terasa',
        'tej' => 'Tektonik Jauh',
        'dev' => 'Double Event',
        'gtb' => 'Getaran Banjir',
        'hrm' => 'Harmonik',
        'dpt' => 'Deep Tremor',
        'mtr' => 'Tremor Menerus'
    ];

    protected $code_gempa;

    protected $nama_gempa;

    protected $code_sp = [
        'tej',
        'tel',
        'dev',
        'vta',
        'hyb'
    ];

    protected $code_normal = [
        'vlp',
        'dpt',
        'vtb',
        'lof',
        'tor',
        'hrm',
        'tre',
        'hbs',
        'gtb'
    ];

    protected $code_dominan = ['mtr'];

    protected $code_luncuran = [
        'gug',
        'apg',
        'apl'
    ];

    protected $code_erupsi = ['lts'];
    
    protected $code_terasa = ['trs'];

    protected function minMax($array)
    {
        ($array[0] == $array[1]) AND ($array[1] >0) ?
            $result = $array[1] :
            $result = '';

        ($array[0] != $array[1]) AND ($array[0] >0) ?
            $result = $array[0].'-'.$array[1] :
            $result = $array[1];

        return $result;
    }

    protected function spMinMax()
    {
        ($this->sp[0] == $this->sp[1]) AND ($this->sp[1] >0) ?
            $result = $this->sp[1].' detik' :
            $result = 'tidak teramati';

        ($this->sp[0] != $this->sp[1]) AND ($this->sp[0] >0) ?
            $result = $this->sp[0].'-'.$this->sp[1].' detik' :
            $result = $this->sp[1].' detik';

        return $result;
    }

    protected function setGempa($code_gempa)
    {
        $this->code_gempa = $code_gempa;
        return $this;
    }

    protected function setNamaGempa()
    {
        foreach ($this->codes as $code_gempa => $nama_gempa) {
            if ($code_gempa == $this->code_gempa)
            {
                $this->nama_gempa = $nama_gempa;
                return $this;
            }
        }
    }
    protected function setJumlahGempa()
    {
        $this->jumlah = $this->var->{'var_'.$this->code_gempa};
        return $this;
    }

    protected function setAmplitudoGempa()
    {
        $this->amplitudo = [
            $this->var->{'var_'.$this->code_gempa.'_amin'},
            $this->var->{'var_'.$this->code_gempa.'_amax'}
        ];

        return $this;
    }

    protected function setAmplitudoDominan()
    {
        $this->amplitudo_dominan = $this->var->{'var_'.$this->code_gempa.'_adom'};
        return $this;
    }

    protected function setSp()
    {
        $this->sp = [
            $this->var->{'var_'.$this->code_gempa.'_spmin'},
            $this->var->{'var_'.$this->code_gempa.'_spmax'}
        ];

        return $this;
    }

    protected function setDurasi()
    {
        $this->durasi = [
            $this->var->{'var_'.$this->code_gempa.'_dmin'},
            $this->var->{'var_'.$this->code_gempa.'_dmax'}
        ];

        return $this;
    }

    protected function setSkala()
    {
        $this->skala = str_replace_last(', ',' dan ', strtolower(implode(', ',$this->var->{'var_'.$this->code_gempa.'_skalamin'})));
        return $this;
    }

    protected function setLuncuran()
    {
        $this->jarak_luncuran = [
            $this->var->{'var_'.$this->code_gempa.'_rmin'},
            $this->var->{'var_'.$this->code_gempa.'_rmax'}
        ];

        $this->arah_luncuran = $this->var->{'var_'.$this->code_gempa.'_alun'};

        $arah = is_null($this->arah_luncuran) ? 'arah luncuran tidak teramati. ' : ' dan arah luncuran teramati ke arah '.$this->arah_luncuran.'. ';

        ($this->jarak_luncuran[0] == $this->jarak_luncuran[1]) AND ($this->jarak_luncuran[1] >5) ?
            $this->luncuran = 'Teramati guguran dengan jarak luncur '.$this->jarak_luncuran[1].' meter, '.$arah :
            $this->luncuran = 'Jarak luncur dan arah guguran tidak teramati. ';

        ($this->jarak_luncuran[0] != $this->jarak_luncuran[1]) AND ($this->jarak_luncuran[0] >0) ?
            $this->luncuran = 'Teramati guguran dengan jarak luncur '.$this->jarak_luncuran[0].'-'.$this->jarak_luncuran[1].' meter, '.$arah :
            $this->luncuran = 'Teramati guguran dengan jarak luncur '.$this->jarak_luncuran[1].' meter, '.$arah;

        return $this;
    }

    protected function getGempaSp()
    {
        return $this->jumlah.' kali gempa '.$this->nama_gempa.' dengan amplitudo '.$this->minMax($this->amplitudo).' mm, S-P '.$this->spMinMax().' dan lama gempa '.$this->minMax($this->durasi).' detik. ';
    }

    protected function getGempaNormal()
    {
        return $this->jumlah.' kali gempa '.$this->nama_gempa.' dengan amplitudo '.$this->minMax($this->amplitudo).' mm, dan lama gempa '.$this->minMax($this->durasi).' detik. ';
    }

    protected function getGempaDominan()
    {
        return $this->jumlah.' kali gempa '.$this->nama_gempa.' dengan amplitudo '.$this->minMax($this->amplitudo).' mm, dominan '.$this->amplitudo_dominan.' mm. ';
    }

    protected function getGempaLuncuran()
    {
        return $this->jumlah.' kali gempa '.$this->nama_gempa.' dengan amplitudo '.$this->minMax($this->amplitudo).' mm dan lama gempa '.$this->minMax($this->durasi).' detik. '.$this->luncuran;
    }

    protected function getGempaLetusan()
    {
        return $this->jumlah.' kali gempa '.$this->nama_gempa.' dengan amplitudo '.$this->minMax($this->amplitudo).' mm, dan lama gempa '.$this->minMax($this->durasi).' detik. ';
    }

    protected function getGempaTerasa()
    {
        return $this->jumlah.' kali gempa '.$this->nama_gempa.', skala '.$this->skala.' dengan amplitudo '.$this->minMax($this->amplitudo).' mm, S-P '.$this->spMinMax().' dan lama gempa '.$this->minMax($this->durasi).' detik. ';
    }

    protected function setDeskripsiGempa($code_gempa)
    {
        if (in_array($code_gempa,$this->code_sp))
        {
            array_push(
                $this->deskripsi_gempa,
                $this->setGempa($code_gempa)
                        ->setNamaGempa()
                        ->setJumlahGempa()
                        ->setAmplitudoGempa()
                        ->setSp()
                        ->setDurasi()
                        ->getGempaSp());

            return $this;
        }

        if (in_array($code_gempa,$this->code_normal))
        {
            array_push(
                $this->deskripsi_gempa,
                $this->setGempa($code_gempa)
                        ->setNamaGempa()
                        ->setJumlahGempa()
                        ->setAmplitudoGempa()
                        ->setDurasi()
                        ->getGempaNormal());

            return $this;
        }

        if (in_array($code_gempa,$this->code_dominan))
        {
            array_push(
                $this->deskripsi_gempa,
                $this->setGempa($code_gempa)
                        ->setNamaGempa()
                        ->setJumlahGempa()
                        ->setAmplitudoGempa()
                        ->setAmplitudoDominan()
                        ->getGempaDominan());

            return $this;
        }

        if (in_array($code_gempa,$this->code_luncuran))
        {
            array_push(
                $this->deskripsi_gempa,
                $this->setGempa($code_gempa)
                        ->setNamaGempa()
                        ->setJumlahGempa()
                        ->setAmplitudoGempa()
                        ->setDurasi()
                        ->setLuncuran()
                        ->getGempaLuncuran());

            return $this;
        }

        if (in_array($code_gempa,$this->code_erupsi))
        {
            array_push(
                $this->deskripsi_gempa,
                $this->setGempa($code_gempa)
                        ->setNamaGempa()
                        ->setJumlahGempa()
                        ->setAmplitudoGempa()
                        ->setDurasi()
                        ->getGempaLetusan());

            return $this;
        }

        if (in_array($code_gempa,$this->code_terasa))
        {
            array_push(
                $this->deskripsi_gempa,
                $this->setGempa($code_gempa)
                        ->setNamaGempa()
                        ->setJumlahGempa()
                        ->setSkala()
                        ->setAmplitudoGempa()
                        ->setSp()
                        ->setDurasi()
                        ->getGempaTerasa());

            return $this;
        }
    }

    protected function getDeskripsiGempa($var)
    {
        $this->var = $var;
        foreach ($this->codes as $code_gempa => $nama_gempa) 
        {
            $var->{'var_'.$code_gempa} > 0 ? 
                    $this->setDeskripsiGempa($code_gempa) : false;
        }
        return $this->deskripsi_gempa;
    }
}