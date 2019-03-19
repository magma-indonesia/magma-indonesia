<?php

namespace App\Http\Controllers\v1\Json;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\v1\MagmaVar as OldVar;
use App\v1\Gadd;
use App\v1\User;
use App\v1\PosPga;
use App\Http\Requests\v1\CreateVar;
use App\Http\Requests\v1\CreateVarRekomendasi;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;

use App\Traits\VisualAsap;
use App\Traits\v1\DeskripsiGempa;

class MapController extends Controller
{
    use VisualAsap,DeskripsiGempa;

    /**
     * Display the specified resource.
     *
     * @param str $ga_code
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        $ga_code = $request->ga_code;

        $var = OldVar::where('ga_code',$ga_code)
                ->orderBy('var_noticenumber','desc')
                ->first();

        $this->failed($var);

        $gadd = Gadd::where('ga_code',$ga_code)->first();

        $asap = (object) [
            'wasap' => isset($var->var_wasap) ? $var->var_wasap->toArray() : [],
            'intasap' => isset($var->var_wasap) ? $var->var_intasap->toArray() : [], 
            'tasap_min' => $var->var_tasap_min,
            'tasap_max' => $var->var_tasap,
        ];

        $visual = $this->visibility($var->var_visibility->toArray())
                    ->asap($var->var_asap, $asap)
                    ->getVisual();

        $klimatologi = $this->clearVisual()->cuaca($var->var_cuaca->toArray())
                    ->angin($var->var_kecangin->toArray(),$var->var_arangin->toArray())
                    ->suhu($var->var_suhumin,$var->var_suhumax)
                    ->kelembaban($var->var_kelembabanmin,$var->var_kelembabanmax)
                    ->getVisual();

        $gempa = $this->getDeskripsiGempa($var);

        $data = [
            'success' => '1',
            'data' => [
                        'nama' => $gadd->ga_nama_gapi,
                        'gunungapi' => 'Terletak di Kab\Kota '.$gadd->ga_kab_gapi.', '.$gadd->ga_prov_gapi.' dengan posisi geografis di Latitude '.$gadd->ga_lat_gapi.', Longitude '.$gadd->ga_lon_gapi.' dan memiliki ketinggian '.$gadd->ga_elev_gapi.' mdpl',
                        'status' => $var->cu_status,
                        'laporan' => 'Laporan per '.$var->var_perwkt.' jam, tanggal '.$var->var_data_date->format('Y-m-d').' pukul '.$var->periode,
                        'visual' => $visual,
                        'visual_lainnya' => $var->Var_ketlain ? $var->Var_ketlain : 'Nihil',
                        'image' => $var->var_image,
                        'gempa' => $gempa,
                        'grafik_gempa' => env('MAGMA_URL').'img/eqhist/'.$gadd->ga_code.'.png',
                        'klimatologi' => $klimatologi,
                        'rekomendasi' => nl2br($var->var_rekom),
                        'pembuat_laporan' => $var->var_nama_pelapor
                        ],
                    ];
        
        return response()->json($data);
    }

    protected function failed($var)
    {
        return empty($var) ?
            response()->json(['success' => 'false', 'message' => 'Data VAR tidak ditemukan'], 500) : 
            $this;
    }

}
