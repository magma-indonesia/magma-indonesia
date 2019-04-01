<?php

namespace App\Http\Controllers\FrontPage\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use App\v1\Vona;

class VonaController extends Controller
{
    //
    protected function filteredVona()
    {

    }

    protected function nonFilteredVona($vona, $page)
    {
        return Cache::remember('v1/home/vona-'.$vona->no.'-'.$page, 30, function() {
            return Vona::where('sent',1)
                ->orderBy('log','desc')
                ->paginate(15);
        });
    }

    public function index(Request $request)
    {
        \Carbon\Carbon::setLocale('en');

        $vona = Vona::select('no','sent','log')
                    ->where('sent',1)
                    ->orderBy('log','desc')
                    ->first();

        $page = $request->has('page') ? $request->page : 1;

        $vonas = $request->has('volcano') || $request->has('code') ?
                    $this->filteredVona() :
                    $this->nonFilteredVona($vona, $page);

        return view('v1.home.vona', compact('vonas'));
    }

    public function show($id)
    {
        $vona = Vona::where('no',$id)->first();
        $vona->addView();
        return view('v1.home.vona-show',compact('vona'));
    }
}
