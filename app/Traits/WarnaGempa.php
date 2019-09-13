<?php

namespace App\Traits;

use Illuminate\Http\Request;

trait WarnaGempa
{
    protected function getColor($key)
    {
        switch($key) {
            case 'lts': return '#F44336'; 
            case 'apl': return '#e91e63'; 
            case 'gug': return '#1976d2'; 
            case 'apg': return '#673ab7'; 
            case 'hbs': return '#3f51b5'; 
            case 'tre': return '#0d47a1'; 
            case 'tor': return '#03a9f4'; 
            case 'lof': return '#006064'; 
            case 'hyb': return '#009688'; 
            case 'vtb': return '#8BC34A'; 
            case 'vta': return '#33691E'; 
            case 'vlp': return '#827717'; 
            case 'tel': return '#F57F17'; 
            case 'trs': return '#FFCA28'; 
            case 'tej': return '#FFA726'; 
            case 'dev': return '#ff5722'; 
            case 'gtb': return '#795548'; 
            case 'hrm': return '#607d8b'; 
            case 'mtr': return '#9E9E9E'; 
            default: return '#333333';
        }
    }
}