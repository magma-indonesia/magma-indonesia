<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

use App\Export\VarExport;
use App\Export\CrsExport;

class ExportController extends Controller
{

    public function index(Request $request, $type)
    {
        switch ($type) {
            case 'crs':
                return $this->crs($request);
                break;
            case 'var':
                return $this->var($request);
                break;
            default:
                # code...
                break;
        }
        
    }

    public function crs($request)
    {
        return (new CrsExport($request))->download('MAGMA_crs_'.now().'.xlsx');
    }

    public function var($request)
    {
        return (new VarExport($request))->download('MAGMA_var_'.now().'.xlsx');
    }
}
