<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

use App\Export\CrsExport;

class ExportController extends Controller
{
    public function crs(string $jenis, string $type = 'GUNUNG API;GEMPA BUMI;TSUNAMI;GERAKAN TANAH;SEMBURAN LUMPUR, GAS, DAN AIR')
    {
        $type = explode(';',$type);
        // return \App\SigertanCrs::all();
        // return $type;
        // return (new CrsExport)->download('MAGMA_crs_'.now().'.xlsx');
        return (new CrsExport(2018,$type))->download('MAGMA_crs_'.now().'.xlsx');
    }
}
