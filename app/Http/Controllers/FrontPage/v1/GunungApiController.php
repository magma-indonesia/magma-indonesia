<?php

namespace App\Http\Controllers\FrontPage\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use App\v1\MagmaVen;
use DB;

class GunungApiController extends Controller
{
    protected function filteredVen($code, $ven, $page)
    {
        MagmaVen::select('ga_code')
                ->where('ga_code',$code)
                ->firstOrFail();

        $vens = Cache::remember('v1/home/vens-filtered-'.$ven->erupt_id.'-page-'.$page.'-'.$code, 120, function() use($code) {
            return MagmaVen::with('gunungapi:ga_code,ga_nama_gapi,ga_zonearea,ga_elev_gapi','user:vg_nip,vg_nama')
                    ->where('ga_code',$code)
                    ->orderBy('erupt_tgl','desc')
                    ->paginate(15);
        });

        return $vens;
    }

    protected function nonFilteredVen($ven, $page)
    {
        $vens = Cache::remember('v1/home/vens-'.$ven->erupt_id.'-page-'.$page, 120, function() {
            return MagmaVen::with('gunungapi:ga_code,ga_nama_gapi,ga_zonearea,ga_elev_gapi','user:vg_nip,vg_nama')
                    ->orderBy('erupt_tgl','desc')
                    ->paginate(15);
        });

        return $vens;
    }

    public function indexVen(Request $request, $code = null)
    {
        $ven = MagmaVen::select('erupt_id')
                ->orderBy('erupt_id','desc')
                ->first();

        $page = $request->has('page') ? $request->page : 1;

        $records = Cache::remember('v1/home/vens-records-'.$ven->erupt_id, 60, function() {
            return MagmaVen::with('gunungapi:ga_code,ga_nama_gapi')
                ->select('ga_code')
                ->distinct('ga_code')
                ->get();
        });

        $vens = $code ?
                    $this->filteredVen($code, $ven, $page) :
                    $this->nonFilteredVen($ven, $page);

        $grouped = $vens->groupBy('erupt_tgl');

        $counts = Cache::remember('v1/home/vens-count-'.$ven->erupt_id, 120, function () {
            return MagmaVen::with('gunungapi:ga_code,ga_nama_gapi')
                    ->select('ga_code','erupt_tgl', DB::raw('count(*) as total'))
                    ->where('erupt_tgl','like','%'.now()->format('Y').'%')
                    ->groupBy('ga_code')
                    ->orderBy('total','desc')
                    ->get();
        });

        return view('v1.home.letusan',compact('vens','grouped','counts','records'));
    }
}
