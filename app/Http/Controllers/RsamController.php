<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use App\Gadd;
use App\Seismometer;
use Ping;

class RsamController extends Controller
{
    public function index()
    {
        $health = Ping::check(config('app.winston_host'));

        $gadds = Cache::remember('gadds.seismometers', 1440, function() {
            return Gadd::whereHas('seismometers')
                    ->withCount('seismometers')
                    ->with('seismometers:code,scnl')->select('name','code')
                    ->orderBy('name')
                    ->get();
        });
        
        return view('gunungapi.rsam.index', compact('gadds','health'));
    }
}
