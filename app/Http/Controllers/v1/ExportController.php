<?php

namespace App\Http\Controllers\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\MagmaVar as NewVar;

class ExportController extends Controller
{
    public function index()
    {
        $vars = NewVar::with(
            'gunungapi','pj','verifikator','visual','visual.asap',
            'klimatologi','gempa','rekomendasi','keterangan')
        ->orderBy('created_at')
        ->whereBetween('var_data_date',['2018-05-24',now()->format('Y-m-d')])
        ->paginate(5);

        return $vars;
    }
}
