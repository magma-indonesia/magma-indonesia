<?php

namespace App\Http\Controllers\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use App\v1\Gadd;
use App\v1\MagmaVar as OldVar;
use App\v1\GertanCrs as Crs;
use App\v1\MagmaRoq as Roq;
use App\HomeKrb;
use App\PublicCheckLocation as Check;
use Illuminate\Support\Facades\DB;

use App\Traits\v1\GunungApiTerdekat;

class HomeController extends Controller
{

    use GunungApiTerdekat;

    protected function cacheHomeKrb()
    {
        return Cache::rememberForever('home:krb', function () {
            return HomeKrb::latest()->first(); 
        });
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function home()
    {
        $home_krb = $this->cacheHomeKrb();
        $last_var = OldVar::select('no','var_log')->orderBy('no','desc')->first();
        $last_roq = Roq::select('no','datetime_wib','roq_logtime')
                        ->orderBy('datetime_wib','desc')
                        ->first();

        $last_gertan = Crs::select('idx','crs_log')->orderBy('idx','desc')->first();

        $gadds = Cache::remember('v1/home/gadd', 120, function() {
            return Gadd::select(
                'ga_code','ga_nama_gapi','ga_kab_gapi',
                'ga_prov_gapi','ga_koter_gapi','ga_elev_gapi',
                'ga_lon_gapi','ga_lat_gapi','ga_status')
            ->whereNotIn('ga_code',['TEO'])
            ->orderBy('ga_nama_gapi','asc')
            ->get();
        });

        $ga_code = $gadds->pluck('ga_code');

        $vars = Cache::remember('v1/home/var:'.strtotime($last_var->var_log), 60, function() {
            $sub = OldVar::select('ga_code', DB::raw('MAX(var_noticenumber) AS latest_date'))->groupBy('ga_code');
            return OldVar::select('no', 'magma_var.ga_code', 'cu_status', 'var_data_date', 'periode', 'var_perwkt', 'var_noticenumber', 'var_nama_pelapor')
                ->join(DB::raw("({$sub->toSql()}) latest_table"), function ($join) {
                    $join->on('latest_table.ga_code', '=', 'magma_var.ga_code')
                        ->on('latest_table.latest_date', '=', 'magma_var.var_noticenumber');
                })->get();
        });

        $vona = Gadd::whereHas('vona', function ($query) {
            $query->whereBetween('log',[now()->subWeek(),now()]);
        })->select('ga_code','ga_nama_gapi')->get();

        $gadds = $gadds->map(function ($gadd, $key) use($vars,$vona) {
            $var = $vars->where('ga_code',$gadd->ga_code)->first();
            $vona = $vona->where('ga_code',$gadd->ga_code)->first();
            $gadd->ga_status = $var->cu_status;
            $gadd->has_vona = isset($vona->ga_code) ? true : false ;
            return $gadd;
        });

        $gertans = Cache::remember('v1/home/sigertan:'.strtotime($last_gertan->crs_log), 60, function() {
            return Crs::select('idx','crs_ids','crs_lat','crs_lon','crs_log','crs_prv','crs_cty')
                    ->has('tanggapan')
                    ->with('tanggapan')
                    ->where('crs_sta','TERBIT')
                    ->whereBetween('crs_lat',[-12, 2])
                    ->whereBetween('crs_lon',[89, 149])
                    ->orderBy('crs_log','desc')
                    ->limit(30)
                    ->get();
        });

        $gempas = Cache::remember('v1/home/gempa:'.strtotime($last_roq->roq_logtime), 60, function() {
            return Roq::orderBy('datetime_wib','desc')->limit(30)->get();
        });
        
        return view('v1.home.home',compact('gadds','gertans','gempas','home_krb'));
    }

    public function index()
    {
        return view('v1.home.home-index');
    }

    public function frame()
    {
        $home_krb = $this->cacheHomeKrb();
        $last_var = OldVar::select('no','var_log')->orderBy('no','desc')->first();
        $last_roq = Roq::select('no','datetime_wib','roq_logtime')
                        ->orderBy('datetime_wib','desc')
                        ->first();

        $last_gertan = Crs::select('idx','crs_log')->orderBy('idx','desc')->first();

        $gadds = Cache::remember('v1/home/gadd', 120, function() {
            return Gadd::select(
                'ga_code','ga_nama_gapi','ga_kab_gapi',
                'ga_prov_gapi','ga_koter_gapi','ga_elev_gapi',
                'ga_lon_gapi','ga_lat_gapi','ga_status')
            ->whereNotIn('ga_code',['TEO'])
            ->orderBy('ga_nama_gapi','asc')
            ->get();
        });

        $ga_code = $gadds->pluck('ga_code');

        $vars = Cache::remember('v1/home/var:'.strtotime($last_var->var_log), 60, function() use($ga_code) {
            return OldVar::select(DB::raw('t.*'))
                ->from(DB::raw('(SELECT no,ga_code,cu_status,var_data_date,periode,var_perwkt,var_noticenumber,var_nama_pelapor FROM magma_var ORDER BY var_noticenumber DESC) t'))
                ->whereIn('ga_code',$ga_code)
                ->groupBy('t.ga_code')
                ->get();
        });

        $vona = Gadd::whereHas('vona', function ($query) {
            $query->whereBetween('log',[now()->subWeek(),now()]);
        })->select('ga_code','ga_nama_gapi')->get();

        $gadds = $gadds->map(function ($gadd, $key) use($vars,$vona) {
            $var = $vars->where('ga_code',$gadd->ga_code)->first();
            $vona = $vona->where('ga_code',$gadd->ga_code)->first();
            $gadd->ga_status = $var->cu_status;
            $gadd->has_vona = isset($vona->ga_code) ? true : false ;
            return $gadd;
        });

        $gertans = Cache::remember('v1/home/sigertan:'.strtotime($last_gertan->crs_log), 60, function() {
            return Crs::select('idx','crs_ids','crs_lat','crs_lon','crs_log','crs_prv','crs_cty')
                    ->has('tanggapan')
                    ->with('tanggapan')
                    ->where('crs_sta','TERBIT')
                    ->whereBetween('crs_lat',[-12, 2])
                    ->whereBetween('crs_lon',[89, 149])
                    ->orderBy('crs_log','desc')
                    ->limit(30)
                    ->get();
        });

        $gempas = Cache::remember('v1/home/gempa:'.strtotime($last_roq->roq_logtime), 60, function() {
            return Roq::orderBy('datetime_wib','desc')->limit(30)->get();
        });
        
        return view('v1.home.home-frame',compact('gadds','gertans','gempas','home_krb'));
    }

    public function check(Request $request)
    {
        $validator = $this->validate($request, [
            'name' => 'required|min:4',
            'latitude' => 'required|numeric|between:-21.41,14.3069',
            'longitude' => 'required|numeric|between:73.65,153.41'
        ],[
            'name.required' => 'Form Nama Lokasi belum diisi',
            'latitude.required' => 'Latitude belum diisi',
            'longitude.required' => 'Longitude belum diisi',
            'name.min' => 'Minimal 4 karakter',
            'latitude.numeric' => 'Latitude harus berformat angka',
            'longitude.numeric' => 'Longitude harus berformat angka',
            'longitude.between' => 'Nilai Longitude antara 73.65 - 153.41 BT',
            'latitude.between' => 'Nilai Latitude antara -21.41 - 14.3069 LU',
        ]);

        $check = Check::updateOrCreate(
            [
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
            ],
            [
                'name' => $request->name,
                'ip_address' => $request->ip()
            ]);
        
        $volcano = $this->getGunungApiTerdekat($request->latitude,$request->longitude);

        if ($check) 
            return response()->json([
                'success' => 1,
                'message' => $check,
                'volcano' => $volcano ?: null
            ]);
        
        return response()->json([
            'success' => 0,
            'message' => $validator->errors()->all()
        ]);
    }
}
