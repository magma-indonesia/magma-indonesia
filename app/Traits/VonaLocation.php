<?php

namespace App\Traits;

trait VonaLocation
{
    /**
     * Convert longitude and latitude to decimal text
     *
     * @return lat,lon string
     */
    protected function location($type,$decimal)
    {
        $vars = explode(".",$decimal);
        $deg = $vars[0];
        $tempma = "0.".$vars[1];
        $tempma = $tempma * 3600;
        $min = floor($tempma / 60);
        $sec = round($tempma - ($min*60));

        $sym = "N ";
        if ($deg<10 AND $deg>0) {
            $deg="0".$deg;
        } else {
            if ($deg<0){
                $deg="0".abs($deg);
                $sym="S ";
            }
        }
        if ($min<10) {
            $min="0".$min;
        }
        if ($sec<10) {
            $sec="0".$sec;
        }

        if ($type == 'lat') {
            return $sym.$deg.' deg '.$min.' min '.$sec.' sec '; 
        }
        
        return 'E '.$deg.' deg '.$min.' min '.$sec.' sec'; 
    }
}