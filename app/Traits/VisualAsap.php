<?php

namespace App\Traits;

trait VisualAsap
{
    protected $visual;

    protected function cuaca($data)
    {
        count($data)>1 ? 
            $result = 'Cuaca '.strtolower($data[0]).' hingga '.strtolower(last($data)).',' : 
            $result = 'Cuaca '.strtolower($data[0]).',';
       
       $this->visual .= $result;
       return $this;
    }

    protected function curah($curah)
    {
        $curah ? 
            $result = 'Intensitas curah hujan '.$curah.' mm per hari. ' :
            $result = '';
        
        $this->visual .= $result;
        return $this;         
    }

    protected function angin($kecepatan,$arah)
    {
        count($kecepatan)>1 ? 
            $result = ' angin '.strtolower($kecepatan[0]).' hingga '.strtolower(last($kecepatan)) : 
            $result = ' angin '.strtolower($kecepatan[0]);

        count($arah)>1 ?
            $result = $result.' ke arah '.str_replace_last(', ',' dan ', strtolower(implode(', ',$arah))).'. ' : 
            $result = $result.' ke arah '.strtolower($arah[0]).'. ';
       $this->visual .= $result;
       return $this;
    }

    protected function suhu($min,$max)
    {
        ($min == $max) AND ($max >0) ?
            $result = 'Suhu udara sekitar '.$max.'&deg;C. ' :
            $result = '';

        ($min != $max) AND ($min >0) ?
            $result = 'Suhu udara sekitar '.$min.'-'.$max.'&deg;C. ' :
            $result = 'Suhu udara sekitar '.$max.'&deg;C. ';

       $this->visual .= $result;
       return $this;
    }

    protected function kelembaban($min,$max)
    {
        ($min == $max) AND ($max >0) ?
            $result = 'Kelembaban '.$max.'%. ' :
            $result = '';

        ($min != $max) AND ($min >0) ?
            $result = 'Kelembaban '.$min.'-'.$max.'%. ' :
            $result = 'Kelembaban '.$max.'%. ';

       $this->visual .= $result;
       return $this;
    }

    protected function tekanan($min,$max)
    {
        ($min == $max) AND ($max >0) ?
            $result = 'Tekanan udara '.$max.' mmHg. ' :
            $result = '';

        ($min != $max) AND ($min >0) ?
            $result = 'Tekanan udara '.$min.'-'.$max.' mmHg. ' :
            $result = 'Tekanan udara '.$max.' mmHg. ';

       $this->visual .= $result;
       return $this;
    }

    protected function visibility($data)
    {
        $data[0] == 'Jelas' ? $var = 'terlihat jelas' : $var = 'tertutup '.$data[0];

        count($data)>1 ? 
            $result = 'Gunung api '.$var.' hingga tertutup '.last($data).'. ' : 
            $result = 'Gunung api '.$var.'. ';

       $this->visual .= $result;
       return $this;
    }

    protected function asap($data,$asap)
    {
        switch ($data) {
            case 'Nihil':
                $result = 'Asap kawah nihil. ';
                break;
            case 'Tidak Teramati':
                $result = 'Asap kawah tidak teramati. ';
                break;
            default:
                $result = 'Teramati asap kawah utama berwarna '.str_replace_last(', ',' dan ', strtolower(implode(', ',$asap->wasap))).' dengan intensitas '.str_replace_last(', ',' hingga ', strtolower(implode(', ',$asap->intasap))).$this->tinggiasap($asap->tasap_min,$asap->tasap_max);
                break;
        }

       $this->visual .= $result;
       return $this;
    }

    protected function warnaAsapLetusan($wasap)
    {
        if ($wasap->isEmpty())
        return ' kolom abu tidak teramati. ';

        $result = $wasap->toArray()[0] != '-' ? ' kolom abu letusan berwarna '.str_replace_last(', ',' hingga ', strtolower(implode(', ',$wasap->toArray()))).'. ' : ' kolom abu tidak teramati. ';
        
        return $result;
    }

    protected function tinggiLetusan($min,$max,$wasap)
    {
        ($min == $max) AND ($max >0) ?
            $result = 'Terjadi Letusan dengan tinggi '.$max.' meter dari puncak,'.$this->warnaAsapLetusan($wasap):
            $result = 'Terekam Gempa Letusan, namun secara visual tinggi letusan dan warna abu tidak teramati. ';

        ($min != $max) AND ($min >0) ?
            $result = 'Terjadi Letusan dengan tinggi '.$min.'-'.$max.' meter dari puncak,'.$this->warnaAsapLetusan($wasap) :
            $result = 'Terjadi Letusan dengan tinggi '.$max.' meter dari puncak,'.$this->warnaAsapLetusan($wasap);

       return $result;
    }

    protected function letusan($var, $result = '')
    {
        $result = $var->var_lts ? $this->tinggiLetusan($var->var_lts_tmin,$var->var_lts_tmax,$var->var_lts_wasap) : '';

        $this->visual .= $result;
        return $this;
    }

    protected function arahLuncuran($arah)
    {
        $result = !empty($arah) ? ' dan arah luncuran ke arah '.str_replace_last(', ',' hingga ', strtolower(implode(', ',$arah->toArray()))).'. ' : ' arah luncuran tidak teramati. ';
        
        return $result;
    }

    protected function panjangLuncuran($nama_gempa, $min,$max,$arah)
    {
        ($min == $max) AND ($max >0) ?
            $result = $nama_gempa.' teramati dengan jarak luncur '.$max.' meter dari puncak,'.$this->arahLuncuran($arah):
            $result = 'Terjadi '.$nama_gempa.', namun secara visual, jarak dan arah luncuran tidak teramati. ';

        ($min != $max) AND ($min >0) ?
            $result = $nama_gempa.' teramati dengan jarak luncur '.$min.'-'.$max.' meter dari puncak,'.$this->arahLuncuran($arah) :
            $result = $nama_gempa.' teramati dengan jarak luncur '.$max.' meter dari puncak,'.$this->arahLuncuran($arah);

       return $result;
    }

    protected function luncuran($var, $code_gempa = ['apl','apg','gug'], $result = '')
    {
        $this->visual_array = [];

        $nama_gempa = [
            'apl' => 'Awan Panas Letusan',
            'apg' => 'Awan Panas Guguran',
            'gug' => 'Guguran',
        ];

        foreach ($code_gempa as $code) {
            $result = $var->{'var_'.$code} ? $this->panjangLuncuran($nama_gempa[$code], $var->{'var_'.$code.'_rmin'},$var->{'var_'.$code.'_rmax'},$var->{'var_'.$code.'_alun'}) : '';

            if ($result)
            {
                array_push($this->visual_array, $result);
            }
        }

        return $this;
    }

    protected function tinggiasap($min,$max)
    {
        ($min == $max) AND ($max >0) ?
            $result = ' tinggi sekitar '.$max.' meter dari puncak. ':
            $result = ' tinggi asap tidak teramati. ';

        ($min != $max) AND ($min >0) ?
            $result = ' tinggi sekitar '.$min.'-'.$max.' meter dari puncak. ' :
            $result = ' tinggi sekitar '.$max.' meter dari puncak. ';

       return $result;
    }

    public function getVisual()
    {
        return $this->visual;
    }

    public function getVisualArray()
    {
        return $this->visual_array;
    }

    public function clearVisual()
    {
        $this->visual = '';
        return $this;
    }
}