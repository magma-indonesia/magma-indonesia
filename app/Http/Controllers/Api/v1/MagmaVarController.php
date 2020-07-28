<?php

namespace App\Http\Controllers\Api\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\v1\MagmaVar as OldVar;
use App\v1\MagmaVarOptimize;
use App\v1\Gadd;
use App\Http\Resources\v1\MagmaVarResource;
use App\Http\Resources\v1\MagmaVarCollection;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

use App\Traits\VisualAsap;
use App\Traits\v1\DeskripsiGempa;

class MagmaVarController extends Controller
{

    use VisualAsap,DeskripsiGempa;

    protected function varAsap($var)
    {
        return (object) [
            'wasap' => isset($var->var_wasap) ? $var->var_wasap->toArray() : [],
            'intasap' => isset($var->var_wasap) ? $var->var_intasap->toArray() : [], 
            'tasap_min' => $var->var_tasap_min,
            'tasap_max' => $var->var_tasap0,
        ];
    }

    protected function varVisual($var)
    {
        return $this->visibility($var->var_visibility->toArray())
            ->asap($var->var_asap, $this->varAsap($var))
            ->getVisual();
    }

    protected function varKlimatologi($var)
    {
        return $this->clearVisual()->cuaca($var->var_cuaca->toArray())
                ->angin($var->var_kecangin->toArray(),$var->var_arangin->toArray())
                ->suhu($var->var_suhumin,$var->var_suhumax)
                ->kelembaban($var->var_kelembabanmin,$var->var_kelembabanmax)
                ->getVisual();
    }

    protected function indexResponse(Collection $vars)
    {
        $vars->transform(function($var) {
            $gempa = $this->getDeskripsiGempa($var);

            return [
                'gunung_api' => [
                    'nama' => $var->gunungapi->ga_nama_gapi,
                    'deskripsi' => 'Terletak di Kab\Kota '.$var->gunungapi->ga_kab_gapi.', '.$var->gunungapi->ga_prov_gapi.' dengan posisi geografis di Latitude '.$var->gunungapi->ga_lat_gapi.'&deg;LU, Longitude '.$var->gunungapi->ga_lon_gapi.'&deg;BT dan memiliki ketinggian '.$var->gunungapi->ga_elev_gapi.' mdpl',
                    'status' => $var->cu_status,
                    'koordinat' => [
                        'latitude' => $var->gunungapi->ga_lat_gapi,
                        'longitude' => $var->gunungapi->ga_lon_gapi
                    ]
                ],
                'laporan_terakhir' => [
                    'tanggal' => 'Laporan per '.$var->var_perwkt.' jam, tanggal '.$var->var_data_date->format('Y-m-d').' pukul '.$var->periode.' '.$var->gunungapi->ga_zonearea,
                    'dibuat_oleh' =>  $var->var_nama_pelapor,
                    'visual' => [
                        'deskripsi' => $this->varVisual($var),
                        'lainnya' => $var->var_ketlain ? title_case($var->var_ketlain) : 'Nihil',
                        'foto' => $var->var_image,
                    ],
                    'klimatologi' => [
                        'deskripsi' => $this->varKlimatologi($var),
                    ],
                    'gempa' => [
                        'deskripsi' => empty($gempa) ? ['Kegempaan nihil.'] : $gempa,
                        'grafik' => env('MAGMA_URL').'img/eqhist/'.$var->gunungapi->ga_code.'.png',
                    ],
                    'rekomendasi' => nl2br($var->var_rekom),
                ]
            ];
        });

        return $vars;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $last_var = OldVar::select('no','var_log')->orderBy('no','desc')->first();

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
                ->from(DB::raw('(SELECT * FROM magma_var ORDER BY var_data_date DESC, periode DESC ) t'))
                ->whereIn('ga_code',$ga_code)
                ->groupBy('t.ga_code')
                ->with('gunungapi:ga_code,ga_nama_gapi,ga_kab_gapi,ga_prov_gapi,ga_lat_gapi,ga_lon_gapi,ga_elev_gapi,ga_zonearea')
                ->get();
        });

        return ($request->input('raw', false) == 'true' ) ? $vars : $this->indexResponse($vars);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Vona  $vona
     * @return \Illuminate\Http\Response
     */
    public function show($code, $noticenumber = null)
    {
        if ($noticenumber) {
            $var = Cache::remember('v1/api/var-show-'.$code.$noticenumber, 60, function() use($code,$noticenumber) {
                return MagmaVarOptimize::where('ga_code',$code)
                    ->where('var_noticenumber',$noticenumber)
                    ->firstOrFail();
            });
            
            return new MagmaVarResource($var);
        }

        $var = Cache::remember('v1/api/var-show-'.$code, 60, function () use($code) {
            return MagmaVarOptimize::where('ga_code',$code)
                ->orderBy('var_data_date','desc')
                ->orderBy('periode','desc')
                ->first();
        });

        return new MagmaVarResource($var);
    }
}
