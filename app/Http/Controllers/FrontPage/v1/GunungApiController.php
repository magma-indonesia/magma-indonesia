<?php

namespace App\Http\Controllers\FrontPage\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use App\v1\MagmaVen;
use App\v1\MagmaVar;
use App\Traits\VisualAsap;
use App\Traits\v1\DeskripsiGempa;
use DB;

class GunungApiController extends Controller
{

    use VisualAsap,DeskripsiGempa;

    protected function setVisual($var)
    {
        $asap = (object) [
            'wasap' => isset($var->var_wasap) ? $var->var_wasap->toArray() : [],
            'intasap' => isset($var->var_wasap) ? $var->var_intasap->toArray() : [], 
            'tasap_min' => $var->var_tasap_min,
            'tasap_max' => $var->var_tasap,
        ];

        $this->visual = $this->clearVisual()
                            ->visibility($var->var_visibility->toArray())
                            ->asap($var->var_asap, $asap)
                            ->cuaca($var->var_cuaca->toArray())
                            ->angin($var->var_kecangin->toArray(),$var->var_arangin->toArray())
                            ->getVisual();
        
        return $this;
    }

    protected function getKlimatologi($var)
    {
        return $this->clearVisual()->cuaca($var->var_cuaca->toArray())
            ->angin($var->var_kecangin->toArray(),$var->var_arangin->toArray())
            ->suhu($var->var_suhumin,$var->var_suhumax)
            ->kelembaban($var->var_kelembabanmin,$var->var_kelembabanmax)
            ->getVisual();
    }

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

    protected function filteredVar()
    {

    }

    protected function nonFilteredVar($var, $page, $start, $end)
    {

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

    public function indexVar(Request $request)
    {
        $last = MagmaVar::select('no')->orderBy('no','desc')->first();
        $page = $request->has('page') ? $request->page : 1;

        $vars = Cache::remember('v1/home/vars-'.$last->no.'-page-'.$page, 30, function () {
                return MagmaVar::with('gunungapi:ga_code,ga_zonearea')
                        ->orderBy('var_data_date','desc')
                        ->orderBy('periode','desc')
                        ->paginate(10);
        });

        $grouped = Cache::remember('v1/home/vars-grouped-'.$last->no.'-page-'.$page, 30, function () use ($vars) {
            $grouped = $vars->groupBy('data_date');
            $grouped->each(function ($vars, $key) {
                $vars->transform(function ($var, $key) {
                    $this->setVisual($var);
                    return (object) [
                        'id' => $var->no,
                        'gunungapi' => $var->ga_nama_gapi,
                        'status' => $var->cu_status,
                        'code' => $var->ga_code,
                        'tanggal' => $var->data_date,
                        'tanggal_deskripsi' => $var->var_data_date->formatLocalized('%A, %d %B %Y'),
                        'pelapor' => $var->var_nama_pelapor,
                        'periode' => $var->periode.' '.$var->gunungapi->ga_zonearea,
                        'visual' => $this->visual,
                        'foto' => $var->var_image,
                    ];
                });
            });

            return $grouped;
        });

        return view('v1.home.var',compact('vars','grouped'));
    }

    public function showVar($id)
    {
        $vars = MagmaVar::with('gunungapi:ga_code,ga_kab_gapi,ga_prov_gapi,ga_lat_gapi,ga_lon_gapi,ga_elev_gapi,ga_zonearea')
                    ->whereNo($id)
                    ->firstOrFail();

        $vars = collect([$vars]);

        $vars->transform(function ($var, $key) {
            $this->setVisual($var);
            return (object) [
                'id' => $var->no,
                'gunungapi' => $var->ga_nama_gapi,
                'intro' => 'Terletak di Kab\Kota '.$var->gunungapi->ga_kab_gapi.', '.$var->gunungapi->ga_prov_gapi.' dengan posisi geografis di Latitude '.$var->gunungapi->ga_lat_gapi.'&deg;LU, Longitude '.$var->gunungapi->ga_lon_gapi.'&deg;BT dan memiliki ketinggian '.$var->gunungapi->ga_elev_gapi.' mdpl',
                'status' => $var->cu_status,
                'code' => $var->ga_code,
                'tanggal' => $var->data_date,
                'tanggal_deskripsi' => $var->var_data_date->formatLocalized('%A - %d %B %Y'),
                'pelapor' => $var->var_nama_pelapor,
                'periode' => $var->periode.' '.$var->gunungapi->ga_zonearea,
                'rekomendasi' => $var->var_rekom,
                'visual' => $this->visual,
                'foto' => $var->var_image,
                'visual_lainnya' => $var->var_ketlain ?: 'Nihil',
                'klimatologi' => $this->getKlimatologi($var),
                'gempa' => $this->getDeskripsiGempa($var),
            ];
        });
        // return $vars;

        $var = $vars->first();
        return view('v1.home.var-show', compact('var'));
    }
}
