<?php

namespace App\Http\Controllers\FrontPage\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\v1\Gadd;
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

    protected function filteredVona($request)
    {
        $this->vonas = Vona::where('ga_code',$request->code)
                    ->where('sent',1)
                    ->where('type','REAL')
                    ->orderBy('log','desc')
                    ->paginate(15);

        if ($this->vonas->isEmpty())
            abort(404);

        $this->grouped = $this->vonas->groupBy(function ($vona) {
            return Carbon::parse($vona->issued_time)->format('Y-m-d');
        });

        return $this;
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
                        ->where('type','REAL')
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

        $request->has('code') ?
                $this->filteredVona($request) :
                $this->nonFilteredVona($request);

        $vonas = $this->getVonas();
        $grouped = $this->getGrouped();
        $gadds = Gadd::select('ga_code','ga_nama_gapi')
                        ->whereHas('vona', function($query) {
                            $query->where('sent',1)->where('type','REAL');
                        })
                        ->orderBy('ga_nama_gapi')
                        ->withCount('vona')
                        ->get();

        return view('v1.home.vona', compact('vonas','grouped','gadds'));
    }

    public function show($id)
    {
        $vona = Vona::where('no',$id)->first();
        
        if ($vona->isEmpty()) {
            return abort(404);
        }

        $vona->addView();
        return view('v1.home.vona-show',compact('vona'));
    }
}
