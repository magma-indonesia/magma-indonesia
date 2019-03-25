<?php

namespace App\Http\Controllers\v1\Json;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\v1\MagmaVar as OldVar;
use App\v1\Vona;
use App\v1\Gadd;
use App\v1\User;
use App\v1\PosPga;
use App\v1\GertanCrs as Crs;
use App\v1\MagmaSigertan;
use App\v1\MagmaRoq;
use App\Http\Requests\v1\CreateVar;
use App\Http\Requests\v1\CreateVarRekomendasi;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;

use App\Traits\VisualAsap;
use App\Traits\v1\DeskripsiGempa;

class MapController extends Controller
{
    use VisualAsap,DeskripsiGempa;

    public function showGempa(Request $request) 
    {
        $id = $request ? $request->id : $id;

        $roq = Cache::remember('v1/json/show-roq-'.$id, 60, function() use($id) {
            return MagmaRoq::where('no',$id)->first();
        });

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
                    'sumber' => $roq->roq_source,
                    'intensitas' => $roq->mmi ? $roq->mmi : 'Belum ada data.',
                    'has_tanggapan' => $roq->roq_tanggapan == 'YA' ? true : false,
                ],
                'tanggapan' => [
                    'tsunami' => (empty($roq->roq_tsu) || $roq->roq_tsu=='TIDAK') ? 'Tidak berpotensi Tsunami' : 'Berpotensi terjadi Tsunami',
                    'pendahuluan' => $roq->roq_tanggapan == 'YA' ? $roq->roq_intro : 'Belum ada tanggapan.',
                    'kondisi' => $roq->roq_tanggapan == 'YA' ? $roq->roq_konwil :  'Belum ada tanggapan.',
                    'mekanisme' => $roq->roq_tanggapan == 'YA' ? $roq->roq_mekanisme :  'Belum ada tanggapan.',
                    'efek' => $roq->roq_tanggapan == 'YA' ? $roq->roq_efek :  'Belum ada tanggapan.',
                ],
                'rekomendasi' => $roq->roq_tanggapan == 'YA' ? nl2br($roq->roq_rekom) :  'Belum ada rekomendasi.',
            ]
        ];

        return $data;
    }

    public function showSigertan(Request $request)
    {
        $crs_id = $request ? $request->id : $id;

        $gertan = Cache::remember('v1/json/show-sigertan-'.$crs_id, 60, function() use($crs_id) {
            return Crs::with('tanggapan')->where('crs_ids',$crs_id)->first();
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
     * Display the specified resource.
     *
     * @param str $ga_code
     * @return \Illuminate\Http\Response
     */
    public function showVar(Request $request)
    {
        $ga_code = $request->ga_code;
        $var = OldVar::select('var_log')->where('ga_code',$ga_code)
                            ->orderBy('var_noticenumber','desc')
                            ->first();

        $vona = Vona::select('log')
                    ->where('sent',1)
                    ->where('ga_code',$ga_code)
                    ->orderBy('log','desc')
                    ->first();

        $var = Cache::remember('v1/json/show-var-'.$ga_code.'/'.$var->var_log, 30, function() use($ga_code) {
            return OldVar::where('ga_code',$ga_code)
                    ->orderBy('var_noticenumber','desc')
                    ->first();
        });

        if ($vona) {
            $vona = Cache::remember('v1/json/show-vona-'.$ga_code.'/'.$vona->log, 30, function() use($ga_code) {
                return Vona::select(
                    'issued','cu_avcode','volcanic_act_summ',
                    'vc_height_text','other_vc_info','remarks')
                ->where('ga_code',$ga_code)
                ->where('type','REAL')
                ->where('sent',1)
                ->orderBy('log','desc')
                ->first();
            });
        }

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
                'gunungapi' => [
                    'nama' => $gadd->ga_nama_gapi,
                    'deskripsi' => 'Terletak di Kab\Kota '.$gadd->ga_kab_gapi.', '.$gadd->ga_prov_gapi.' dengan posisi geografis di Latitude '.$gadd->ga_lat_gapi.'&deg;LU, Longitude '.$gadd->ga_lon_gapi.'&deg;BT dan memiliki ketinggian '.$gadd->ga_elev_gapi.' mdpl',
                    'status' => $var->cu_status,
                    'koordinat' => [$gadd->ga_lat_gapi,$gadd->ga_lon_gapi],
                    'has_vona' => !empty($vona) ? '1' : '0',
                ],
                'laporan' => [
                    'tanggal' => 'Laporan per '.$var->var_perwkt.' jam, tanggal '.$var->var_data_date->format('Y-m-d').' pukul '.$var->periode,
                    'pembuat' =>  $var->var_nama_pelapor,
                ],
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
                'vona' => $vona,
            ]
        ];
        
        return response()->json($data);
    }

    public function hasVona(Request $request)
    {
        return $gadds = Gadd::whereHas('one_vona', function ($query) {
                            $query->whereBetween('log',[now()->subWeek(),now()]);
                        })
                        ->select('ga_code','ga_nama_gapi')
                        ->get();
    }

    public function hasEruptions(Request $request)
    {
        return $eruptions = Gadd::whereHas('var', function ($query) {
                                $query->where('var_lts','>=',1)
                                    ->whereBetween('var_data_date',[now()->subWeek(),now()]);
                            })->select('ga_code','ga_nama_gapi')->get();
    }

    protected function failed($var)
    {
        return empty($var) ?
            response()->json(['success' => 'false', 'message' => 'Data VAR tidak ditemukan'], 500) : 
            $this;
    }

}
