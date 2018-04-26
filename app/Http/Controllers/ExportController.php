<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

use App\Export\CrsExport;

class ExportController extends Controller
{
    public function crs(Request $request)
    {
        return (new CrsExport($request))->download('MAGMA_crs_'.now().'.xlsx');
    }
}
