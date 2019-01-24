<?php

namespace App\Http\Controllers\api\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\v1\MagmaSigertan;
use App\v1\GertanCrs;
use App\Http\Resources\v1\MagmaSigertanResource;
use App\Http\Resources\v1\MagmaSigertanCollection;

class MagmaSigertanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $sigertans = GertanCrs::with('tanggapan')
                        ->orderBy('crs_log','desc')
                        ->paginate(5);
                        
        return new MagmaSigertanCollection($sigertans);
    }
}
