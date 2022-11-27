<?php

namespace App\Http\Controllers\FrontPage\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\v1\PressRelease as Press;

class PressController extends Controller
{
    public function index(Request $request)
    {
        $presses = Press::orderBy('id','desc')->simplePaginate(5);
        $randoms = Press::inRandomOrder()->limit(5)->get();
        return view('v1.home.press', compact('presses','randoms'));
    }

    public function show($id, $slug = null)
    {
        $press = is_null($slug) ?
            Press::where('id', $id)->firstOrFail() :
            Press::where('id', $id)->where('slug', $slug)->firstOrFail();

        $randoms = Press::inRandomOrder()->limit(5)->get();
        $press->addView();

        return view('v1.home.press-show', compact('press','randoms'));
    }
}
