<?php

namespace App\Http\Controllers\Api\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use App\v1\Gadd;
use App\v1\MagmaVar as OldVar;
use App\v1\Vona;
use App\v1\GertanCrs as Crs;
use App\v1\MagmaRoq;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;

use App\Traits\VisualAsap;
use App\Traits\v1\DeskripsiGempa;

class HomeController extends Controller
{
    use VisualAsap,DeskripsiGempa;

    /**
     * Display the Api/Home/Gunung Api
     *
     * @return \Illuminate\Http\Response
     */
    public function gunungapi()
    {

        $last_var = OldVar::select('no','var_log')->orderBy('no','desc')->first();
        $last_vona = Vona::select('log')->orderBy('log','desc')->first();

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
                ->from(DB::raw('(SELECT ga_code,cu_status,var_data_date,periode,var_perwkt,var_noticenumber,var_nama_pelapor FROM magma_var ORDER BY var_noticenumber DESC) t'))
                ->whereIn('ga_code',$ga_code)
                ->groupBy('t.ga_code')
                ->get();
        });

        $vona = Cache::remember('v1/home/vona:'.strtotime($last_vona->log), 60, function() {
            return Gadd::whereHas('vona', function ($query) {
                $query->whereBetween('log',[now()->subWeek(),now()]);
            })->select('ga_code','ga_nama_gapi')->get();
        });

        $gadds = Cache::remember('v1/home/gadd:'.strtotime($last_var->var_log), 60, function() use ($gadds, $vars, $vona){
            $gadds = $gadds->map(function ($gadd, $key) use ($vars, $vona) {
                $var = $vars->where('ga_code',$gadd->ga_code)->first();
                $vona = $vona->where('ga_code',$gadd->ga_code)->first();
                $gadd->ga_status = $var->cu_status;
                $gadd->has_vona = isset($vona->ga_code) ? true : false ;
                $gadd->url = route('api.v1.home.gunung-api.var.show',['code'=> $gadd->ga_code]);
                return $gadd;
            });

            return $gadds;
        });

        return $gadds;
    }

    public function gunungapiStatus()
    {
        $gadds = $this->gunungapi();

        return [
            'url' => route('v1.gunungapi.tingkat-aktivitas'),
            'latest' => [
                [
                    'level' => 4,
                    'status' => 'Awas',
                    'jumlah' => $gadds->where('ga_status',4)->count(),
                    'daftar_gunungapi' => $gadds->where('ga_status',4)->pluck('ga_nama_gapi')
                ],[
                    'level' => 3,
                    'status' => 'Siaga',
                    'jumlah' => $gadds->where('ga_status',3)->count(),
                    'daftar_gunungapi' => $gadds->where('ga_status',3)->pluck('ga_nama_gapi')
                ],[
                    'level' => 2,
                    'status' => 'Waspada',
                    'jumlah' => $gadds->where('ga_status',2)->count(),
                    'daftar_gunungapi' => $gadds->where('ga_status',2)->pluck('ga_nama_gapi')
                ],[
                    'level' => 1,
                    'status' => 'Normal',
                    'jumlah' => $gadds->where('ga_status',1)->count(),
                    'daftar_gunungapi' => $gadds->where('ga_status',1)->pluck('ga_nama_gapi')
                ]
            ]
        ];
    }

    /**
     * Display the Api/Home/Gerakan Tanah
     *
     * @return \Illuminate\Http\Response
     */
    public function gerakanTanah()
    {
        $last_gertan = Crs::select('idx','crs_log')->orderBy('idx','desc')->first();

        $gertans = Cache::remember('v1/home/sigertan:'.strtotime($last_gertan->crs_log), 60, function() {
            $gertans = Crs::select('idx','crs_ids','crs_lat','crs_lon','crs_log','crs_prv','crs_cty')
                    ->has('tanggapan')
                    ->with('tanggapan')
                    ->where('crs_sta','TERBIT')
                    ->whereBetween('crs_lat',[-12, 2])
                    ->whereBetween('crs_lon',[89, 149])
                    ->orderBy('crs_log','desc')
                    ->limit(30)
                    ->get();

            $gertans = $gertans->map(function ($gertan, $key) {
                $gertan->url = route('api.v1.home.gerakan-tanah.sigertan.show',['id'=> $gertan->crs_ids]);
                return $gertan;
            });

            return $gertans;
        });

        return $gertans;
    }

    public function gerakanTanahLatest()
    {
        $gertan = Crs::has('tanggapan')
                ->where('crs_sta','TERBIT')
                ->whereBetween('crs_lat',[-12, 2])
                ->whereBetween('crs_lon',[89, 149])
                ->orderBy('crs_log','desc')
                ->first();

        $judul = $gertan->crs_rgn.', '.$gertan->crs_cty.', '.$gertan->crs_prv;

        return [
            'lokasi' => $judul,
            'date' => $gertan->crs_dtm->format('Y-m-d H:i:s'),
            'zone' => $gertan->crs_zon,
            'url' => URL::signedRoute('v1.gertan.sigertan.show', ['id' => $gertan->crs_ids]),
        ];
    }

    /**
     * Display the Api/Home/Gempa Bumi
     *
     * @return \Illuminate\Http\Response
     */
    public function gempaBumi()
    {
        $last_roq = MagmaRoq::select('no','datetime_wib','roq_logtime')
                        ->orderBy('datetime_wib','desc')
                        ->first();

        $gempas = Cache::remember('v1/home/gempa:'.strtotime($last_roq->roq_logtime), 60, function() {
            $roqs = MagmaRoq::select('no','datetime_wib','datetime_utc',
                            'roq_image','id_lap','magnitude','magtype',
                            'depth','dep_unit','lon_lima','lat_lima',
                            'area','koter','mmi','nearest_volcano',
                            'roq_tanggapan','roq_source','roq_logtime')
                        ->orderBy('datetime_wib','desc')
                        ->limit(30)
                        ->get();
            $roqs = $roqs->map(function($roq, $key) {
                $roq->url = route('api.v1.home.gempa-bumi.roq.show',['id'=> $roq->no]);
                return $roq;
            });
            return $roqs;
        });

        return $gempas;
    }

    public function gempaBumiLatest()
    {
        $roq = MagmaRoq::orderBy('datetime_wib','desc')->first();

        return [
            'lokasi' => $roq->magnitude.' SR, '.$roq->depth.' Km '.$roq->area,
            'date' => $roq->datetime_wib->format('Y-m-d H:i:s'),
            'zone' => 'WIB',
            'url' => URL::signedRoute('v1.gempabumi.roq.show', ['id' => $roq->no]),
        ];
    }

    /**
     * Display the specified resource.
     *
     * @param str $code
     * @return \Illuminate\Http\Response
     */
    public function showVar($ga_code)
    {
        $var = OldVar::select('var_log')->where('ga_code',$ga_code)
                            ->orderBy('var_noticenumber','desc')
                            ->firstOrFail();

        $vona = Vona::select('log')
                    ->where('sent',1)
                    ->where('ga_code',$ga_code)
                    ->whereBetween('log',[now()->subWeek(),now()])
                    ->orderBy('log','desc')
                    ->first();

        $var = Cache::remember('v1/json/show:var:'.$ga_code.':'.strtotime($var->var_log), 30, function() use($ga_code) {
            return OldVar::where('ga_code',$ga_code)
                    ->orderBy('var_noticenumber','desc')
                    ->firstOrFail();
        });

        if ($vona) {
            $vona = Cache::remember('v1/json/show:vona:'.$ga_code.':'.strtotime($vona->log), 30, function() use($ga_code) {
                return Vona::select(
                    'issued','cu_avcode','volcanic_act_summ',
                    'vc_height_text','other_vc_info','remarks')
                ->where('ga_code',$ga_code)
                ->where('type','REAL')
                ->where('sent',1)
                ->orderBy('log','desc')
                ->firstOrFail();
            });
        }

        $this->failed($var);

        $gadd = Gadd::where('ga_code',$ga_code)->firstOrFail();

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

        $vona = !empty($vona) ? [
            'issued' => $vona->issued,
            'color_code' => $vona->cu_avcode,
            'summary' => $vona->volcanic_act_summ,
            'vch' => $vona->vc_height_text,
            'other_vch' => $vona->other_vc_info,
            'remarks' => $vona->remarks,
        ] : [];

        $data = [
            'success' => '1',
            'data' => [
                'gunung_api' => [
                    'nama' => $gadd->ga_nama_gapi,
                    'deskripsi' => 'Terletak di Kab\Kota '.$gadd->ga_kab_gapi.', '.$gadd->ga_prov_gapi.' dengan posisi geografis di Latitude '.$gadd->ga_lat_gapi.'&deg;LU, Longitude '.$gadd->ga_lon_gapi.'&deg;BT dan memiliki ketinggian '.$gadd->ga_elev_gapi.' mdpl',
                    'status' => $var->cu_status,
                    'koordinat' => [
                        'latitude' => $gadd->ga_lat_gapi,
                        'longitude' => $gadd->ga_lon_gapi
                    ],
                    'has_vona' => !empty($vona) ? true : false,
                ],
                'laporan_terakhir' => [
                    'tanggal' => 'Laporan per '.$var->var_perwkt.' jam, tanggal '.$var->var_data_date->format('Y-m-d').' pukul '.$var->periode.' '.$gadd->ga_zonearea,
                    'dibuat_oleh' =>  $var->var_nama_pelapor,
                    'visual' => [
                        'deskripsi' => $visual,
                        'lainnya' => $var->var_ketlain ? title_case($var->var_ketlain) : 'Nihil',
                        'foto' => $var->var_image,
                    ],
                    'klimatologi' => [
                        'deskripsi' => $klimatologi,
                    ],
                    'gempa' => [
                        'deskripsi' => empty($gempa) ? ['Kegempaan nihil.'] : $gempa,
                        'grafik' => env('MAGMA_URL').'img/eqhist/'.$gadd->ga_code.'.png',
                    ],
                    'rekomendasi' => nl2br($var->var_rekom),
                ],
                'vona' => $vona,
            ]
        ];

        return response()->json($data);
    }

    /**
     * Display the Api/Home/Gerakan Tanah
     *
     * @param str $crs_id
     * @return \Illuminate\Http\Response
     */
    public function showSigertan(Request $request, $id)
    {
        $crs_id = $request ? $request->id : $id;

        $gertan = Cache::remember('v1/json/show:sigertan:'.$crs_id, 60, function() use($crs_id) {
            return Crs::with('tanggapan')->where('crs_ids',$crs_id)->firstOrFail();
        });

        $korban = $gertan->tanggapan->qls_kmd ? '<p>'.$gertan->tanggapan->qls_kmd.' korban meninggal dunia.</p>' : '';
        $luka = $gertan->tanggapan->qls_kll ? '<p>'.$gertan->tanggapan->qls_kll.' korban luka-luka.</p>' : '';
        $rumah_rusak = $gertan->tanggapan->qls_rrk ? '<p>'.$gertan->tanggapan->qls_rrk.' unit rumah rusak.</p>' : '';
        $rumah_hancur = $gertan->tanggapan->qls_rhc ? '<p>'.$gertan->tanggapan->qls_rhc.' unit rumah hancur.</p>' : '';
        $rumah_terancam = $gertan->tanggapan->qls_rtr ? '<p>'.$gertan->tanggapan->qls_rtr.' unit rumah terancam.</p>' : '';
        $bangunan_rusak = $gertan->tanggapan->qls_blr ? '<p>'.$gertan->tanggapan->qls_blr.' unit bangunan rusak.</p>' : '';
        $bangunan_hancur = $gertan->tanggapan->qls_blh ? '<p>'.$gertan->tanggapan->qls_blh.' unit bangunan hancur.</p>' : '';
        $bangunan_terancam = $gertan->tanggapan->qls_bla ? '<p>'.$gertan->tanggapan->qls_bla.' unit bangunan terancam.</p>' : '';
        $lahan_rusak = $gertan->tanggapan->qls_llp ? '<p>'.$gertan->tanggapan->qls_llp.' lahan pertanian rusak.</p>' : '';
        $jalan_rusak = $gertan->tanggapan->qls_pjr ? '<p>'.$gertan->tanggapan->qls_pjr.' meter jalan rusak.</p>' : '';

        $dampak = $korban.$luka.$rumah_rusak.$rumah_hancur.$rumah_terancam.$bangunan_hancur.$bangunan_rusak.$bangunan_terancam.$lahan_rusak.$jalan_rusak;

        $struktur = $gertan->tanggapan->qls_str ? ' Struktur berupa '.$gertan->tanggapan->qls_str : '';

        $kedalaman_air = $gertan->tanggapan->qls_dep ? ' dengan kedalaman air tanah sekitar '.$gertan->tanggapan->qls_dep.' meter di bawah permukaan.' : '.';

        $data = [
            'success' => '1',
            'data' => [
                'laporan' => [
                    'peta' => empty($gertan->tanggapan->qls_pst) ? 'https://magma.vsi.esdm.go.id/img/empty_sgt.png' : $gertan->tanggapan->qls_pst,
                    'pelapor' => $gertan->crs_usr,
                    'judul' => 'Laporan Tanggapan Gerakan Tanah di '.$gertan->crs_vil.', '.$gertan->crs_rgn.', '.$gertan->crs_cty.', '.$gertan->crs_prv,
                    'updated_at' => 'Diperbarui pada tanggal '.$gertan->crs_log->formatLocalized('%d %B %Y').' pukul '.$gertan->crs_log->format('H:i:s').' WIB',
                    'deskripsi' => 'Gerakan tanah terjadi di '.$gertan->crs_vil.', '.$gertan->crs_rgn.', '.$gertan->crs_cty.', '.$gertan->crs_prv.' pada tanggal '.$gertan->crs_log->format('Y-m-d').' pukul '.$gertan->crs_log->format('H:i:s').' '.$gertan->crs_zon.'. Secara Geografis, lokasi kejadian gerakan tanah terletak pada posisi '.$gertan->crs_lat.' LU dan '.$gertan->crs_lon.' BT.'
                ],
                'tanggapan' => [
                    'tipe' => empty($gertan->tanggapan->qls_tgt) ? 'Belum dilaporkan.' : $gertan->tanggapan->qls_tgt,
                    'dampak' => $dampak ? $dampak : 'Belum ada informasi mengenai dampak dari kejadian gerakan tanah ini.',
                    'kondisi' => [
                        'morfologi' => empty($gertan->tanggapan->qls_sba) ? 'Belum dilaporkan.' : 'Secara umum lokasi gerakan tanah ini merupakan daerah '.strtolower(implode(', ',$gertan->tanggapan->qls_sba)).' yang memiliki kemiringan lereng '.implode(', ',$gertan->tanggapan->qls_mrl),
                        'geologi' => empty($gertan->tanggapan->qls_frm) ? 'Belum dilaporkan.' : 'Berdasarkan peta geologi, lokasi bencana tersusun oleh formasi '.$gertan->tanggapan->qls_frm.'. Jenis Batuan di antaranya adalah '.$gertan->tanggapan->qls_jbt.'. Jenis Pelapukan berupa '.$gertan->tanggapan->qls_jtp.'.'.$struktur,
                        'keairan' => empty($gertan->tanggapan->qls_air) ? 'Belum dilaporkan.' : 'Keairan di lokasi gerakan tanah berupa '.implode(', ',$gertan->tanggapan->qls_air).$kedalaman_air,
                        'tata_guna_lahan' => empty($gertan->tanggapan->qls_tgl) ? 'Belum dilaporkan.' : 'Tata Guna Lahan  di lokasi gerakan tanah ini berupa '.implode(', ',$gertan->tanggapan->qls_tgl).'.',
                        'kerentanan' => empty($gertan->tanggapan->qls_zkg) ? 'Belum dilaporkan.' : 'Berdasarkan Peta Potensi Gerakan Tanah yang dikeluarkan Badan Geologi, Pusat Vulkanologi dan Mitigasi Bencana Geologi pada bulan ini, lokasi bencana berada pada Zona Potensi Gerakan Tanah '.str_replace_last(', ',' hingga ',title_case(implode(', ',$gertan->tanggapan->qls_zkg))).'. Yang artinya daerah ini memiliki potensi '.strtolower(str_replace_last(', ',' hingga ',implode(', ',$gertan->tanggapan->qls_zkg))).' untuk terjadi gerakan tanah.',
                        'penyebab' => empty($gertan->tanggapan->qls_cau) ? 'Belum dilaporkan.' : title_case(implode('<br>',$gertan->tanggapan->qls_cau))
                    ]
                ],
                'rekomendasi' => empty($gertan->tanggapan->rekomendasi) ? 'Belum ada rekomendasi.' : nl2br($gertan->tanggapan->rekomendasi->qls_rec)
            ]
        ];

        return $data;
    }

    /**
     * Display the Api/Home/Gerakan Tanah
     *
     * @param str $id
     * @return \Illuminate\Http\Response
     */
    public function showGempaBumi(Request $request, $id)
    {
        $id = $request ? $request->id : $id;

        $roq = Cache::remember('v1/json/show:roq:'.$id, 60, function() use($id) {
            $roq = MagmaRoq::where('no',$id)->firstOrFail();
            $data = [
                'success' => '1',
                'data' => [
                    'laporan' => [
                        'title' => $roq->area,
                        'pelapor' => $roq->roq_nama_pelapor ? $roq->roq_nama_pelapor : 'Belum ada.',
                        'waktu' => $roq->datetime_wib->formatLocalized('%d %B %Y, %H:%I:%M').' WIB',
                        'kota_terdekat' => $roq->koter ? $roq->koter : 'Belum ada data.',
                        'latitude' => $roq->lat_lima.'&deg;LU',
                        'longitude' => $roq->lon_lima.'&deg;BT',
                        'kedalaman' => $roq->depth.' Km',
                        'magnitude' => $roq->magnitude.' SR',
                        'gunung_terdekat' => $roq->nearest_volcano ? $roq->nearest_volcano : 'Belum ada data.',
                        'map' => $roq->roq_maplink ? $roq->roq_maplink : '',
                        'sumber' => $roq->roq_source ?: 'Badan Meteorologi, Klimatologi dan Geofisika (BMKG)',
                        'intensitas' => $roq->mmi ? $roq->mmi : 'Belum ada data.',
                        'has_tanggapan' => $roq->roq_tanggapan == 'YA' ? true : false,
                    ],
                    'tanggapan' => [
                        'tsunami' => (empty($roq->roq_tsu) || $roq->roq_tsu=='TIDAK') ? 'Tidak berpotensi Tsunami' : 'Berpotensi terjadi Tsunami',
                        'pendahuluan' => $roq->roq_tanggapan == 'YA' ? str_replace('Â°',' ',$roq->roq_intro) : 'Belum ada tanggapan.',
                        'kondisi' => $roq->roq_tanggapan == 'YA' ? $roq->roq_konwil :  'Belum ada tanggapan.',
                        'mekanisme' => $roq->roq_tanggapan == 'YA' ? $roq->roq_mekanisme :  'Belum ada tanggapan.',
                        'efek' => $roq->roq_tanggapan == 'YA' ? $roq->roq_efek :  'Belum ada tanggapan.',
                    ],
                    'rekomendasi' => $roq->roq_tanggapan == 'YA' ? nl2br($roq->roq_rekom) :  'Belum ada rekomendasi.',
                ]
            ];

            return $data;
        });

        return $roq;
    }

    protected function failed($var)
    {
        return empty($var) ?
            response()->json(['success' => '0', 'message' => 'Data VAR tidak ditemukan'], 500) :
            $this;
    }
}
