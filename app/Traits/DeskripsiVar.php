<?php

namespace App\Traits;

use Illuminate\Http\Request;

trait DeskripsiVar
{
    protected $gempa;

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

    protected function minMax($min,$max)
    {
        ($min == $max) AND ($max >0) ?
            $result = $max :
            $result = '';

        ($min != $max) AND ($min >0) ?
            $result = $min.'-'.$max :
            $result = $max;

        return $result;
    }

    protected function sp($min,$max)
    {
        ($min == $max) AND ($max >0) ?
            $result = $max.' detik' :
            $result = 'tidak teramati';

        ($min != $max) AND ($min >0) ?
            $result = $min.'-'.$max.' detik' :
            $result = $max.' detik';

        return $result;
    }

    protected function namaGempa($code)
    {
        foreach ($this->codes as $key=>$name) {
            if ($key == $code)
            {
                return $name;
            }
        }
    }

    protected function gempaSp($data,$code)
    {
        $result = $data['jumlah'].' kali gempa '.$this->namaGempa($code).' dengan amplitudo '.$this->minMax($data['amin'],$data['amax']).' mm, S-P '.$this->sp($data['spmin'],$data['spmax']).' dan lama gempa '.$this->minMax($data['dmin'],$data['dmax']).' detik. ';

        return $result;
    }

    protected function gempaNormal($data,$code)
    {
        $result = $data['jumlah'].' kali gempa '.$this->namaGempa($code).' dengan amplitudo '.$this->minMax($data['amin'],$data['amax']).' mm dan lama gempa '.$this->minMax($data['dmin'],$data['dmax']).' detik. ';

        return $result;
    }

    protected function gempaDominan($data,$code)
    {
        $result = 'Tremor Menerus dengan amplitudo '.$this->minMax($data['amin'],$data['amax']).' mm, dominan '.$data['adom'].' mm. ';

        return $result;
    }

    protected function arah($arah)
    {
        count($arah)>1 ? 
            $result = str_replace_last(', ',' dan ', strtolower(implode(', ',$arah))) :
            $result = strtolower($arah[0]);

       return $result;
    }

    protected function arahLuncuran($arah)
    {
        is_null($arah) ? $result = 'arah luncuran tidak teramati. ' : $result = ' dan arah luncuran teramati ke arah '.$this->arah($arah).'. ';

        return $result;
    }

    protected function jarakLuncur($min,$max,$arah)
    {
        ($min == $max) AND ($max >5) ?
            $result = 'Teramati guguran dengan jarak luncur '.$max.' meter, '.$this->arahLuncuran($arah) :
            $result = 'Jarak luncur dan arah guguran tidak teramati. ';

        ($min != $max) AND ($min >0) ?
            $result = 'Teramati guguran dengan jarak luncur '.$min.'-'.$max.' meter, '.$this->arahLuncuran($arah) :
            $result = 'Teramati guguran dengan jarak luncur '.$max.' meter, '.$this->arahLuncuran($arah);

        return $result;
    }

    protected function gempaLuncuran($data,$code)
    {
        $result = $data['jumlah'].' kali gempa '.$this->namaGempa($code).' dengan amplitudo '.$this->minMax($data['amin'],$data['amax']).' mm dan lama gempa '.$this->minMax($data['dmin'],$data['dmax']).' detik. '.$this->jarakLuncur($data['rmin'],$data['rmax'],$data['arah']);

        return $result;
    }

    protected function gempaErupsi($data,$code)
    {
        $result = $data['jumlah'].' kali gempa '.$this->namaGempa($code).' dengan amplitudo '.$this->minMax($data['amin'],$data['amax']).' mm dan lama gempa '.$this->minMax($data['dmin'],$data['dmax']).' detik. ';

        return $result;
    }

    protected function skala($skala)
    {
        $result = str_replace_last(', ',' dan ', strtolower(implode(', ',$skala)));

        return $result;
    }

    protected function gempaTerasa($data,$code)
    {
        $result = $data['jumlah'].' kali gempa '.$this->namaGempa($code).', skala '.$$this->skala($data['skala']).' dengan amplitudo '.$this->minMax($data['amin'],$data['amax']).' mm, S-P '.$this->sp($data['spmin'],$data['spmax']).' dan lama gempa '.$this->minMax($data['dmin'],$data['dmax']).' detik. ';

        return $result;
    }

    protected function deskripsi($gempa)
    {
        $sp = ['tej','tel','dev','vta','hyb'];
		$normal = ['vlp','dpt','vtb','lof','tor','hrm','tre','hbs','gtb'];
		$dominan = ['mtr'];
		$luncuran = ['gug','apg','apl'];
		$erupsi = ['lts'];
        $terasa = ['trs'];

        $gempa = collect($gempa)->toArray();
        foreach ($gempa as $code=>$data) 
        {
            $result = '';
            if (!empty($data))
            {
                in_array($code,$sp) ?
                    $result = $this->gempaSp($data,$code) : $result .= '';

                in_array($code,$normal) ?
                    $result = $this->gempaNormal($data,$code) : $result .= '';

                in_array($code,$dominan) ?
                    $result = $this->gempaDominan($data,$code) : $result .= '';

                in_array($code,$luncuran) ?
                    $result = $this->gempaLuncuran($data,$code) : $result .= '';

                in_array($code,$erupsi) ?
                    $result = $this->gempaErupsi($data,$code) : $result .= '';

                in_array($code,$terasa) ?
                    $result = $this->gempaTerasa($data,$code) : $result .= '';

                $this->gempa .= $result;
            }
        }

        return $this;

    }

    public function getGempa()
    {
        return $this->gempa;
    }
}