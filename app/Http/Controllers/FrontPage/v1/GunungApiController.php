<?php

namespace App\Http\Controllers\FrontPage\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Cache;
use Validator;
use DB;
use App\v1\Gadd;
use App\v1\MagmaVen;
use App\v1\MagmaVar;
use App\v1\MagmaVarOptimize;

use App\Traits\VisualAsap;
use App\Traits\v1\DeskripsiGempa;

class GunungApiController extends Controller
{

    use VisualAsap,DeskripsiGempa;

    protected $vars;
    protected $grouped;

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
            ->tekanan($var->var_tekananmin,$var->var_tekananmax)
            ->curah($var->var_curah_hujan)
            ->getVisual();
    }

    protected function filteredVen($code, $ven, $page)
    {
        $vens = Cache::remember('v1/home/vens:filtered:'.$ven->erupt_id.':'.$page.':'.$code, 120, function() use($code) {
            return MagmaVen::with('gunungapi:ga_code,ga_nama_gapi,ga_zonearea,ga_elev_gapi','user:vg_nip,vg_nama')
                    ->where('ga_code',$code)
                    ->orderBy('erupt_tgl','desc')
                    ->orderBy('erupt_jam','desc')
                    ->paginate(15);
        });

        return $vens;
    }

    protected function nonFilteredVen($ven, $page)
    {
        $vens = Cache::remember('v1/home/vens:'.$ven->erupt_id.':'.$page, 120, function() {
            return MagmaVen::with('gunungapi:ga_code,ga_nama_gapi,ga_zonearea,ga_elev_gapi','user:vg_nip,vg_nama')
                    ->orderBy('erupt_tgl','desc')
                    ->orderBy('erupt_jam','desc')
                    ->paginate(15);
        });

        return $vens;
    }

    protected function filteredVar($request)
    {
        $validator = Validator::make($request->all(), [
            'code' => 'required|size:3|exists:magma.ga_dd,ga_code',
            'start' => 'required|date_format:Y-m-d|before_or_equal:'.$request->end,
            'end' => 'required|date_format:Y-m-d|before_or_equal:'.now()->format('Y-m-d'),
        ]);

        if ($validator->fails()) {
            abort(404);
        }

        $code = $request->code;
        $page = $request->has('page') ? $request->page : 1;
        $start = strtotime($request->start);
        $end = strtotime($request->end);

        $vars = Cache::remember('v1/home/vars:search:'.$page.':'.$code.':'.$start.':'.$end, 120, function () use($request) {
                return MagmaVar::with('gunungapi:ga_code,ga_zonearea')
                        ->where('ga_code',$request->code)
                        ->whereBetween('var_data_date',[$request->start,$request->end])
                        ->orderBy('var_data_date','desc')
                        ->orderBy('no','desc')
                        ->paginate(10);
        });

        $grouped = Cache::remember('v1/home/vars:grouped:search:'.$page.':'.$code.':'.$start.':'.$end, 120, function () use ($vars) {
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

        $this->vars = $vars;
        $this->grouped = $grouped;

        return $this;
    }

    protected function nonFilteredVar($request)
    {
        $last = MagmaVar::select('no','var_log')->orderBy('no','desc')->first();
        $page = $request->has('page') ? $request->page : 1;

        $date = strtotime($last->var_log);

        $vars = Cache::remember('v1/home/vars:'.$last->no.':'.$page.':'.$date, 120, function () {
                return MagmaVar::with('gunungapi:ga_code,ga_zonearea')
                        ->orderBy('var_data_date','desc')
                        ->orderBy('no','desc')
                        ->simplePaginate(10);
        });

        $grouped = Cache::remember('v1/home/vars:grouped:'.$last->no.':'.$page, 120, function () use ($vars) {
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

        $this->vars = $vars;
        $this->grouped = $grouped;

        return $this;
    }

    protected function getVars()
    {
        return $this->vars;
    }

    protected function getGrouped()
    {
        return $this->grouped;
    }

    protected function getQuery($url)
    {
        $str = parse_url($url)['query'];
        parse_str($str, $query);

        return $query;
    }

    public function indexVen(Request $request, $code = null)
    {
        $ven = MagmaVen::select('erupt_id')
                ->orderBy('erupt_id','desc')
                ->first();

        $page = $request->has('page') ? $request->page : 1;

        $records = Cache::remember('v1/home/vens:records:'.$ven->erupt_id, 60, function() {
            return MagmaVen::with('gunungapi:ga_code,ga_nama_gapi')
                ->select('ga_code')
                ->distinct('ga_code')
                ->get();
        });

        $vens = $code ?
                    $this->filteredVen($code, $ven, $page) :
                    $this->nonFilteredVen($ven, $page);

        $grouped = $vens->groupBy('erupt_tgl');

        $counts = Cache::remember('v1/home/vens:count:'.$ven->erupt_id, 120, function () {
            return MagmaVen::with('gunungapi:ga_code,ga_nama_gapi')
                    ->select('ga_code','erupt_tgl', DB::raw('count(*) as total'))
                    ->where('erupt_tgl','like','%'.now()->format('Y').'%')
                    ->groupBy('ga_code')
                    ->orderBy('total','desc')
                    ->get();
        });

        return view('v1.home.letusan',compact('vens','grouped','counts','records'));
    }

    public function indexVar(Request $request, $q = null)
    {
        $gadds = Cache::remember('v1/home/gadds', 120, function() {
            return Gadd::select('ga_code','ga_nama_gapi')
                    ->orderBy('ga_nama_gapi')
                    ->get();
        });

        $q === 'q' ? $this->filteredVar($request)
                : $this->nonFilteredVar($request);

        $vars = $this->getVars();
        $grouped = $this->getGrouped();

        // $signed = URL::temporarySignedRoute('v1.gunungapi.var.search',now()->addMinutes(30), ['q' => 'q']);

        // $query = $this->getQuery($signed);

        return view('v1.home.var',compact('gadds','vars','grouped'));
    }

    public function showVar($id)
    {

        $vars = Cache::remember('v1/home/var:show:'.$id, 120, function() use ($id) {
            return MagmaVar::with('gunungapi:ga_code,ga_kab_gapi,ga_prov_gapi,ga_lat_gapi,ga_lon_gapi,ga_elev_gapi,ga_zonearea')
                    ->whereNo($id)
                    ->firstOrFail();
        });

        $vars = collect([$vars]);
        $vars->transform(function ($var, $key) {
            $this->setVisual($var);
            return (object) [
                'id' => $var->no,
                'gunungapi' => $var->ga_nama_gapi,
                'intro' => 'Gunung Api '.$var->ga_nama_gapi.' terletak di Kab\Kota '.$var->gunungapi->ga_kab_gapi.', '.$var->gunungapi->ga_prov_gapi.' dengan posisi geografis di Latitude '.$var->gunungapi->ga_lat_gapi.'&deg;LU, Longitude '.$var->gunungapi->ga_lon_gapi.'&deg;BT dan memiliki ketinggian '.$var->gunungapi->ga_elev_gapi.' mdpl',
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

        $var = $vars->first();
        return view('v1.home.var-show', compact('var'));
    }

}
