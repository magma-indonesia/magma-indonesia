<?php

namespace App\Http\Controllers\FrontPage\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\v1\MagmaVen;
use DB;

class GunungApiController extends Controller
{
    public function indexVen(Request $request)
    {
        $vens = MagmaVen::with('gunungapi:ga_code,ga_nama_gapi,ga_zonearea,ga_elev_gapi','user:vg_nip,vg_nama')
                ->orderBy('erupt_tgl','desc')
                ->paginate(30);

        $grouped = $vens->groupBy('erupt_tgl');

        $counts = MagmaVen::with('gunungapi:ga_code,ga_nama_gapi')
                ->select('ga_code','erupt_tgl', DB::raw('count(*) as total'))
                ->where('erupt_tgl','like','%'.now()->format('Y').'%')
                ->groupBy('ga_code')
                ->orderBy('total','desc')
                ->get();

        return view('v1.home.letusan',compact('vens','grouped','counts'));
    }
}
