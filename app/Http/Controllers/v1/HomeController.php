<?php

namespace App\Http\Controllers\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use App\v1\Gadd;
use App\v1\PosPga;

class HomeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $gadds = Cache::remember('v1/gadds', 120, function () {
            return Gadd::select('no','ga_code','ga_nama_gapi')
                ->whereNotIn('ga_code',['TEO','SBG'])
                ->orderBy('ga_nama_gapi','asc')
                ->get();
        });

        return view('v1.home.index',compact('gadds'));
    }
}
