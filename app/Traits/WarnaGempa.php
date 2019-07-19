<?php

namespace App\Traits;

use Illuminate\Http\Request;

trait WarnaGempa
{
    protected function getColor($key)
    {
        switch($key) {
            case 'lts': return $color = '#F44336'; 
            case 'apl': return $color = '#e91e63'; 
            case 'gug': return $color = '#1976d2'; 
            case 'apg': return $color = '#673ab7'; 
            case 'hbs': return $color = '#3f51b5'; 
            case 'tre': return $color = '#0d47a1'; 
            case 'tor': return $color = '#03a9f4'; 
            case 'lof': return $color = '#006064'; 
            case 'hyb': return $color = '#009688'; 
            case 'vtb': return $color = '#8BC34A'; 
            case 'vta': return $color = '#33691E'; 
            case 'vlp': return $color = '#827717'; 
            case 'tel': return $color = '#F57F17'; 
            case 'trs': return $color = '#FFCA28'; 
            case 'tej': return $color = '#FFA726'; 
            case 'dev': return $color = '#ff5722'; 
            case 'gtb': return $color = '#795548'; 
            case 'hrm': return $color = '#607d8b'; 
            case 'mtr': return $color = '#9E9E9E'; 
            default: return $color = '#333333';
        }
    }
}