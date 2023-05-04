<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\v1\Api\MagmaVarFilterRequest;
use App\Traits\v1\DeskripsiGempa;
use App\Traits\VisualAsap;
use App\Traits\WarnaGempa;
use App\v1\MagmaVar as OldVar;
use App\v1\MagmaVarOptimize;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;

class MagmaVarController extends Controller
{

    use VisualAsap,DeskripsiGempa,WarnaGempa;
    protected $series = array();
    protected $graph;

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
        $url = URL::signedRoute('v1.gunungapi.var.show', ['id' => $var->no]);

        return [
            'gunung_api' => [
                'code' => $var->gunungapi->ga_code,
                'nama' => $var->gunungapi->ga_nama_gapi,
                'deskripsi' => 'Terletak di Kab\Kota ' . $var->gunungapi->ga_kab_gapi . ', ' . $var->gunungapi->ga_prov_gapi . ' dengan posisi geografis di Latitude ' . $var->gunungapi->ga_lat_gapi . '&deg;LU, Longitude ' . $var->gunungapi->ga_lon_gapi . '&deg;BT dan memiliki ketinggian ' . $var->gunungapi->ga_elev_gapi . ' mdpl',
                'status' => $var->cu_status,
                'koordinat' => [
                    'latitude' => $var->gunungapi->ga_lat_gapi,
                    'longitude' => $var->gunungapi->ga_lon_gapi
                ],
                'elevation' => $var->gunungapi->ga_elev_gapi,
            ],
            'laporan_terakhir' => [
                'noticenumber' => $var->var_noticenumber,
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
                    'grafik' => config('app.magma_old_url') . 'img/eqhist/' . $var->gunungapi->ga_code . '.png',
                ],
                'rekomendasi' => strip_tags(nl2br($var->var_rekom)),
            ],
            'url' => route('api.v1.magma-var.show', [
                'code' => $var->gunungapi->ga_code,
                'noticenumber' => $var->var_noticenumber,
            ]),
            'share' => [
                'url' =>  $url,
                'description' => "Laporan {$var->var_perwkt} Jam Aktivitas Gunung Api {$var->gunungapi->ga_nama_gapi}. Tanggal {$var->var_data_date->format('Y-m-d')} pukul {$var->periode} {$var->gunungapi->ga_zonearea}. {$url}",
                'photo' => $var->var_image,
            ],
        ];
    }

    /**
     * Undocumented function
     *
     * @param Collection $vars
     * @return void
     */
    protected function transformPaginationData($vars)
    {
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
     * @param  string $code
     * @return Collection
     */
    protected function showByCode($code)
    {
        return Cache::remember('v1/api/var-show-' . $code, 60, function () use ($code) {
            return OldVar::where('ga_code', $code)
                ->orderBy('var_data_date', 'desc')
                ->orderBy('periode', 'desc')
                ->paginate(15);
        });
    }

    /**
     * Display the specified resource.
     *
     * @param  string $code
     * @param  string|null $noticenumber
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

        $vars = $this->showByCode($code);

        return $this->transformPaginationData($vars);
    }

    /**
     * Filter VAR
     *
     * @param \App\Http\Requests\v1\Api\MagmaVarFilterRequest $request
     * @return \Illuminate\Http\Response
     */
    public function filter(MagmaVarFilterRequest $request)
    {
        $validated = $request->validated();

        $vars = OldVar::query();

        $vars->when($request->has('start_date'), function($query) use($validated) {
            $query->whereBetween('var_data_date', [$validated['start_date'], $validated['end_date']]);
        });

        $vars = $vars->where('ga_code', $validated['code'])
            ->where('var_perwkt','24 Jam')
            ->orderBy('var_data_date', 'desc')
            ->orderBy('periode', 'desc')
            ->paginate(15);

        return $this->transformPaginationData($vars);
    }

    /**
     * Simplified Volcanic Seismicity Interface
     *
     * @param string $volCode
     * @return Collection
     */
    public function seismic(string $volCode)
    {

        $start = now()->subDays(90);
        $end = now();
        $start_str = strtotime($start->format('Y-m-d'));
        $end_str = strtotime($end->format('Y-m-d'));
        $categories = $this->setCategories($start, $end);

        $graph = Cache::remember('var:seismic:'.$volCode.':'.$start_str.':'.$end_str, 30, function() use($volCode, $start, $end) {
            return MagmaVarOptimize::select(
                    'var_lts','var_apl','var_gug','var_apg','var_hbs',
                    'var_tre','var_tor','var_lof','var_hyb','var_vtb',
                    'var_vta','var_vlp','var_tel','var_trs','var_tej',
                    'var_dev','var_gtb','var_hrm','var_dpt','ga_code',
                    'var_data_date')
                ->where('ga_code',$volCode)
                ->where('var_perwkt','24 Jam')
                ->whereBetween('var_data_date',[$start->format('Y-m-d'),$end->format('Y-m-d')])
                ->orderBy('var_data_date','asc')
                ->get();
        });

        $keyed = $graph->keyBy('var_data_date');

        $this->graph = $categories->flip()->map(function ($item) {
            return app('App\Http\Controllers\v1\Json\HighCharts')->default_jumlah;
        })->merge($keyed);

        $gempas = collect($this->codes);
        $gempas->each(function ($gempa, $key) {
            $data = $this->getSeries($key);
            if ($data->count) {
                $this->series[] = [
                    'name' => $gempa,
                    'data' => $data->data,
                    'color' => $data->color
                ];
            }
        });

        return collect([
            'categories' => $categories,
            'series' => $this->series
        ]);
    }

    public function setCategories($start, $end)
    {

        $categories = array();
        $dates = new \DatePeriod($start, new \DateInterval('P1D'), $end);

        foreach ($dates as $date)
        {
            $categories[] = $date->format('Y-m-d');
        }

        return collect($categories);
    }

    public function getSeries($key)
    {
        return (object) [
            'count' => $this->graph->sum('var_'.$key),
            'data' => $this->graph->pluck('var_'.$key),
            'color' => $this->getColor($key)
        ];
    }
}
