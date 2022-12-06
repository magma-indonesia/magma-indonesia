<?php

namespace App\Http\Controllers\FrontPage\v1;

use App\HomeKrb;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\v1\Gadd;
use App\v1\MagmaVen;
use App\v1\MagmaVar;
use App\v1\StatistikMagmaVen;

use App\Traits\VisualAsap;
use App\Traits\v1\DeskripsiGempa;
use App\Traits\v1\DeskripsiLetusan;

class GunungApiController extends Controller
{

    use VisualAsap;
    use DeskripsiGempa;
    use DeskripsiLetusan;

    protected $vars;
    protected $grouped;

    protected function cacheHomeKrb()
    {
        return Cache::rememberForever('home:krb', function () {
            return HomeKrb::latest()->first();
        });
    }

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

        if ($vens->isEmpty())
            abort(404, 'Data Letusan tidak ditemukan');

        return $vens;
    }

    protected function nonFilteredVen($ven, $page)
    {
        $last = MagmaVen::select('erupt_id','erupt_tsp')->orderBy('erupt_id','desc')->first();

        $vens = Cache::remember('v1/home/vens:'.$last->erupt_tsp.':'.$page, 120, function() {
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

        $vars = Cache::remember('v1/home/vars:'.$page.':'.$date, 120, function () {
                return MagmaVar::with('gunungapi:ga_code,ga_zonearea')
                        ->orderBy('var_data_date','desc')
                        ->orderBy('no','desc')
                        ->simplePaginate(10);
        });

        $grouped = Cache::remember('v1/home/vars:grouped:'.$page.':'.$date, 120, function () use ($vars) {
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
            return MagmaVen::with('gunungapi:ga_code,ga_nama_gapi,ga_elev_gapi')
                ->select('ga_code')
                ->distinct('ga_code')
                ->get();
        });

        $vens = $code ?
                    $this->filteredVen($code, $ven, $page) :
                    $this->nonFilteredVen($ven, $page);

        $grouped = $vens->groupBy('erupt_tgl');

        $counts = Cache::remember('v1/home/vens:count:'.$ven->erupt_id, 120, function () {
            return MagmaVen::with('gunungapi:ga_code,ga_nama_gapi,ga_elev_gapi')
                    ->select('ga_code','erupt_tgl', DB::raw('count(*) as total'))
                    ->where('erupt_tgl','like','%'.now()->format('Y').'%')
                    ->groupBy('ga_code')
                    ->orderBy('total','desc')
                    ->get();
        });

        return view('v1.home.letusan',compact('vens','grouped','counts','records'));
    }

    public function showVen(Request $request, $id)
    {
        if (is_numeric($id)) {
            abort_unless($request->hasValidSignature(), 404);
            $ven = MagmaVen::with('gunungapi:ga_code,ga_zonearea,ga_nama_gapi,ga_lat_gapi,ga_lon_gapi,ga_elev_gapi')
                ->where('erupt_id', $id)
                ->firstOrFail();
        } else {
            $ven = MagmaVen::with('gunungapi:ga_code,ga_zonearea,ga_nama_gapi,ga_lat_gapi,ga_lon_gapi,ga_elev_gapi')
                ->where('uuid', 'like', $id)
                ->firstOrFail();
        }

        $home_krb = $this->cacheHomeKrb();

        $stats = StatistikMagmaVen::updateOrCreate([
            'erupt_id' => $id
        ],[
            'ga_code' => $ven->gunungapi->ga_code,
        ]);

        $stats->increment('hit');

        return view('v1.home.letusan-show', [
            'ven' => $ven,
            'deskripsi' => $this->deskripsi($ven),
            'home_krb' => $home_krb,
        ]);
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
        $home_krb = $this->cacheHomeKrb();
        $vars = Cache::remember('v1/home/var:show:'.$id, 120, function() use ($id) {
            return MagmaVar::with('gunungapi:ga_code,ga_kab_gapi,ga_prov_gapi,ga_lat_gapi,ga_lon_gapi,ga_elev_gapi,ga_zonearea')
                    ->whereNo($id)
                    ->firstOrFail();
        });

        $var = Cache::remember('v1/home/var:show:transform:'.$id, 120, function() use ($vars) {
            $vars = collect([$vars]);
            $vars->transform(function ($var, $key) {
                $this->setVisual($var);
                return (object) [
                    'id' => $var->no,
                    'gunungapi' => $var->ga_nama_gapi,
                    'loc' => [$var->gunungapi->ga_lat_gapi,$var->gunungapi->ga_lon_gapi],
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

            return $vars->first();
        });

        return view('v1.home.var-show', compact('var','home_krb'));
    }

    public function tingkatAktivitas()
    {
        $last_var = MagmaVar::select('no', 'var_log')->orderBy('no', 'desc')->first();
        $gadds = Cache::remember('v1/home/gadd', 120, function () {
            return Gadd::select(
                'ga_code',
                'ga_nama_gapi',
                'ga_kab_gapi',
                'ga_prov_gapi',
                'ga_koter_gapi',
                'ga_elev_gapi',
                'ga_lon_gapi',
                'ga_lat_gapi',
                'ga_status'
            )
                ->whereNotIn('ga_code', ['TEO'])
                ->orderBy('ga_nama_gapi', 'asc')
                ->get();
        });

        $ga_code = $gadds->pluck('ga_code');

        $vars = Cache::remember('chamber/home:' . strtotime($last_var->var_log), 60, function () {
            $sub = MagmaVar::select('ga_code', DB::raw('MAX(var_noticenumber) AS latest_date'))->groupBy('ga_code');
            return MagmaVar::select('no', 'magma_var.ga_code', 'cu_status', 'var_data_date', 'periode', 'var_perwkt', 'var_noticenumber', 'var_nama_pelapor')
            ->join(DB::raw("({$sub->toSql()}) latest_table"), function ($join) {
                $join->on('latest_table.ga_code', '=', 'magma_var.ga_code')
                ->on('latest_table.latest_date', '=', 'magma_var.var_noticenumber');
            })->get();
        });

        $gadds = $gadds->map(function ($gadd, $key) use ($vars) {
            $var = $vars->where('ga_code', $gadd->ga_code)->first();
            $gadd->ga_status = $var->cu_status;
            $gadd->var_no = $var->no;
            return $gadd;
        });

        return view('v1.home.tingkat-aktivitas', [
            'gadds' => $gadds,
        ]);
    }
}
