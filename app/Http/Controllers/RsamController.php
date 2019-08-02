<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use App\Gadd;

class RsamController extends Controller
{
    public function index()
    {
        $gadds = Cache::remember('gadds', 1440, function() {
            return Gadd::select('name','code')
                    ->get();
        });
        
        return view('gunungapi.rsam.index', compact('gadds'));
    }

    public function store(Request $request)
    {
        return $request;
    }
}
