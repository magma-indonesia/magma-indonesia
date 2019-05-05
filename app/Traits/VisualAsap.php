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
        $wasap[0] != '-' ? $result = ' kolom abu letusan berwarna '.str_replace_last(', ',' hingga ', strtolower(implode(', ',$wasap))).'. ' : $result = ' kolom abu tidak teramati. ';
        
        return $result;
    }

    protected function tinggiLetusan($min,$max,$wasap)
    {
        ($min == $max) AND ($max >0) ?
            $result = 'Teramati <b>Letusan</b> dengan tinggi '.$max.' meter dari puncak,'.$this->warnaAsapLetusan($wasap):
            $result = '<b>Letusan</b> dan warna asap tidak teramati. ';

        ($min != $max) AND ($min >0) ?
            $result = 'Teramati <b>Letusan</b> dengan tinggi '.$min.'-'.$max.' meter dari puncak,'.$this->warnaAsapLetusan($wasap) :
            $result = 'Teramati <b>Letusan</b> dengan tinggi '.$max.' meter dari puncak,'.$this->warnaAsapLetusan($wasap);

       return $result;
    }

    protected function letusan($letusan, $result = '')
    {
        !empty($letusan) ? $result = $this->tinggiLetusan($letusan->tmin,$letusan->tmax,$letusan->wasap) : '';

        $this->visual .= $result;
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

    public function clearVisual()
    {
        $this->visual = '';
        return $this;
    }
}