<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use App\Gadd;
use Ping;

class RsamController extends Controller
{
    public function index()
    {
        $health = Ping::check(config('app.winston_host'));

        $gadds = Cache::remember('gadds', 1440, function() {
            return Gadd::select('name','code')
                    ->get();
        });
        
        return view('gunungapi.rsam.index', compact('gadds','health'));
    }
}
