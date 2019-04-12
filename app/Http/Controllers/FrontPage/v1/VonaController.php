<?php

namespace App\Http\Controllers\FrontPage\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;
use App\v1\Vona;

class VonaController extends Controller
{
    //
    protected $vonas;
    protected $grouped;

    public function __construct()
    {
        \Carbon\Carbon::setLocale('en');
    }

    protected function getVonas()
    {
        return $this->vonas;
    }

    protected function getGrouped()
    {
        return $this->grouped;
    }

    protected function filteredVona()
    {

    }

    protected function nonFilteredVona($request)
    {

        $vona = Vona::select('no','sent','log')
                    ->where('sent',1)
                    ->orderBy('issued_time','desc')
                    ->first();

        $page = $request->has('page') ? $request->page : 1;

        $time = strtotime($vona->log);

        $vonas = Cache::remember('v1/home/vona:'.$vona->no.':'.$page.':'.$time, 30, function() {
                    return Vona::where('sent',1)
                        ->orderBy('log','desc')
                        ->paginate(15);
        });

        $grouped = Cache::remember('v1/home/vona:grouped:'.$vona->no.':'.$page.':'.$time, 30, function() use($vonas) {
            return $vonas->groupBy(function ($vona) {
                return Carbon::parse($vona->issued_time)->format('Y-m-d');
            });
        });

        $this->vonas = $vonas;
        $this->grouped= $grouped;

        return $vonas;
    }

    public function index(Request $request)
    {
        \Carbon\Carbon::setLocale('en');

        $vonas = $request->has('volcano') || $request->has('code') ?
                    $this->filteredVona() :
                    $this->nonFilteredVona($request);

        $vonas = $this->getVonas();
        $grouped = $this->getGrouped();

        return view('v1.home.vona', compact('vonas','grouped'));
    }

    public function show($id)
    {
        $vona = Vona::where('no',$id)->first();
        $vona->addView();
        return view('v1.home.vona-show',compact('vona'));
    }
}
