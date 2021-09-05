<?php

namespace App\Http\Controllers\Api\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\v1\MagmaVar as OldVar;
use App\v1\MagmaVarOptimize;
use App\Http\Resources\v1\MagmaVarResource;
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

    /**
     * Undocumented function
     *
     * @param App\v1\MagmaVar $var
     * @return array
     */
    protected function getVarDescription($var)
    {
        $gempa = $this->clearDeskripsiGempa()->getDeskripsiGempa($var);

        return [
            'gunung_api' => [
                'nama' => $var->gunungapi->ga_nama_gapi,
                'deskripsi' => 'Terletak di Kab\Kota ' . $var->gunungapi->ga_kab_gapi . ', ' . $var->gunungapi->ga_prov_gapi . ' dengan posisi geografis di Latitude ' . $var->gunungapi->ga_lat_gapi . '&deg;LU, Longitude ' . $var->gunungapi->ga_lon_gapi . '&deg;BT dan memiliki ketinggian ' . $var->gunungapi->ga_elev_gapi . ' mdpl',
                'status' => $var->cu_status,
                'koordinat' => [
                    'latitude' => $var->gunungapi->ga_lat_gapi,
                    'longitude' => $var->gunungapi->ga_lon_gapi
                ]
            ],
            'laporan_terakhir' => [
                'tanggal' => 'Laporan per ' . $var->var_perwkt . ' jam, tanggal ' . $var->var_data_date->format('Y-m-d') . ' pukul ' . $var->periode . ' ' . $var->gunungapi->ga_zonearea,
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
                    'grafik' => env('MAGMA_URL') . 'img/eqhist/' . $var->gunungapi->ga_code . '.png',
                ],
                'rekomendasi' => strip_tags(nl2br($var->var_rekom)),
            ],
            'url' => route('api.v1.magma-var.show', [
                'code' => $var->gunungapi->ga_code,
                'noticenumber' => $var->var_noticenumber,
            ]),
        ];
    }

    /**
     * Undocumented function
     *
     * @param Collection $vars
     * @return Collection
     */
    protected function indexResponse(Collection $vars)
    {
        $vars->transform(function($var) {
            return $this->getVarDescription($var);
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
        $last_var = OldVar::select('no','var_log')
            ->orderBy('no','desc')->first();

        $vars = Cache::remember('API/v1/home/var:'.strtotime($last_var->var_log), 60, function() {
            $sub = OldVar::select('ga_code', DB::raw('MAX(var_noticenumber) AS latest_date'))->groupBy('ga_code');
            return OldVar::join(DB::raw("({$sub->toSql()}) latest_table"), function ($join) {
                    $join->on('latest_table.ga_code', '=', 'magma_var.ga_code')
                        ->on('latest_table.latest_date', '=', 'magma_var.var_noticenumber');
                })->with('gunungapi:ga_code,ga_nama_gapi,ga_kab_gapi,ga_prov_gapi,ga_lat_gapi,ga_lon_gapi,ga_elev_gapi,ga_zonearea')
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
            $var = Cache::remember('v1/api/var-show-'.$code.'-'.$noticenumber, 60, function () use($code, $noticenumber) {
                return OldVar::where('ga_code', $code)
                    ->with('gunungapi:ga_code,ga_nama_gapi,ga_kab_gapi,ga_prov_gapi,ga_lat_gapi,ga_lon_gapi,ga_elev_gapi,ga_zonearea')
                    ->where('var_noticenumber', $noticenumber)
                    ->firstOrFail();
            });

            return $this->getVarDescription($var);
        }

        $vars = Cache::remember('v1/api/var-show-'.$code, 60, function () use($code) {
            return OldVar::where('ga_code',$code)
                ->orderBy('var_data_date','desc')
                ->orderBy('periode','desc')
                ->paginate(15);
        });

        $varsTransformed = $this->indexResponse($vars->getCollection())->toArray();

        $varsTransformedAndPaginated = new \Illuminate\Pagination\LengthAwarePaginator(
            $varsTransformed,
            $vars->total(),
            $vars->perPage(),
            $vars->currentPage(),
            [
                'path' => request()->url(),
                'query' => [
                    'page' => $vars->currentPage()
                ]
            ]
        );

        return $varsTransformedAndPaginated;
    }
}
