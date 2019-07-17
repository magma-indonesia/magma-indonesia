<?php

namespace App\Http\Controllers\WOVOdat;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use App\WOVOdat\Volcano;

class VolcanoController extends Controller
{
    public function index()
    {
        $volcanoes = Cache::remember('wovodat.volcano', 1440, function() {
            return Volcano::with('information')->get();
        });
        
        return view('wovodat.volcano.index', compact('volcanoes'));
    }
}
