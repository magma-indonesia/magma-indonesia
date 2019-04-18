<?php

namespace App\Traits;

use Illuminate\Http\Request;

trait WarnaGempa
{
    protected function getColor($key)
    {
        switch($key) {
            case 'lts': return $color = '#F44336'; break;
            case 'apl': return $color = '#e91e63'; break;
            case 'gug': return $color = '#1976d2'; break;
            case 'apg': return $color = '#673ab7'; break;
            case 'hbs': return $color = '#3f51b5'; break;
            case 'tre': return $color = '#0d47a1'; break;
            case 'tor': return $color = '#03a9f4'; break;
            case 'lof': return $color = '#006064'; break;
            case 'hyb': return $color = '#009688'; break;
            case 'vtb': return $color = '#8BC34A'; break;
            case 'vta': return $color = '#33691E'; break;
            case 'vlp': return $color = '#827717'; break;
            case 'tel': return $color = '#F57F17'; break;
            case 'trs': return $color = '#FFCA28'; break;
            case 'tej': return $color = '#FFA726'; break;
            case 'dev': return $color = '#ff5722'; break;
            case 'gtb': return $color = '#795548'; break;
            case 'hrm': return $color = '#607d8b'; break;
            case 'mtr': return $color = '#9E9E9E'; break;
        }
    }
}