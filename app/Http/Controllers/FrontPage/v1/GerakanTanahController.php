<?php

namespace App\Http\Controllers\FrontPage\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;
use App\v1\GertanCrs as Crs;

class GerakanTanahController extends Controller
{
    protected $dampak;
    protected $gertans;
    protected $grouped;

    protected function setDampak($gertan)
    {
        $korban = $gertan->tanggapan->qls_kmd ? $gertan->tanggapan->qls_kmd.' korban meninggal dunia.' : '';
        $luka = $gertan->tanggapan->qls_kll ? $gertan->tanggapan->qls_kll.' korban luka-luka.' : '';
        $rumah_rusak = $gertan->tanggapan->qls_rrk ? $gertan->tanggapan->qls_rrk.' unit rumah rusak.' : '';
        $rumah_hancur = $gertan->tanggapan->qls_rhc ? $gertan->tanggapan->qls_rhc.' unit rumah hancur.' : '';
        $rumah_terancam = $gertan->tanggapan->qls_rtr ? $gertan->tanggapan->qls_rtr.' unit rumah terancam.' : '';
        $bangunan_rusak = $gertan->tanggapan->qls_blr ? $gertan->tanggapan->qls_blr.' unit bangunan rusak.' : '';
        $bangunan_hancur = $gertan->tanggapan->qls_blh ? $gertan->tanggapan->qls_blh.' unit bangunan hancur.' : '';
        $bangunan_terancam = $gertan->tanggapan->qls_bla ? $gertan->tanggapan->qls_bla.' unit bangunan terancam.' : '';
        $lahan_rusak = $gertan->tanggapan->qls_llp ? $gertan->tanggapan->qls_llp.' lahan pertanian rusak.' : '';
        $jalan_rusak = $gertan->tanggapan->qls_pjr ? $gertan->tanggapan->qls_pjr.' meter jalan rusak.' : '';

        $dampak = $korban.$luka.$rumah_rusak.$rumah_hancur.$rumah_terancam.$bangunan_hancur.$bangunan_rusak.$bangunan_terancam.$lahan_rusak.$jalan_rusak;

        $dampak = explode('.',$dampak,-1);

        $this->dampak = !empty($dampak) ? $dampak : [];

        return $this;
    }

    protected function getDampak()
    {
        return $this->dampak;
    }

    protected function getGrouped()
    {
        return $this->grouped;
    }

    protected function getGertans()
    {
        return $this->gertans;
    }

    protected function filteredGertan($request)
    {
        $validator = Validator::make($request->all(), [
            'start' => 'required|date_format:Y-m-d|before_or_equal:'.$request->end,
            'end' => 'required|date_format:Y-m-d|before_or_equal:'.now()->format('Y-m-d'),
        ]);

        if ($validator->fails()) {
            abort(404);
        }

        $page = $request->has('page') ? $request->page : 1;
        $start = strtotime($request->start);
        $end = strtotime($request->end);

        $gertans = Cache::remember('v1/home/sigertan:search:'.$page.':'.$start.':'.$end, 10, function() use($request) {
            return Crs::has('tanggapan')
                    ->with('tanggapan')
                    ->where('crs_sta','TERBIT')
                    ->whereBetween('crs_lat',[-12, 3])
                    ->whereBetween('crs_lon',[89, 149])
                    ->whereBetween('crs_dtm',[$request->start,$request->end])
                    ->orderBy('crs_log','desc')
                    ->simplePaginate(10);
        });

        $grouped = Cache::remember('v1/home/sigertan:search:grouped:'.$page.':'.$start.':'.$end, 10, function() use($gertans) {
            $grouped = $gertans->groupBy('date');
            $grouped->each(function ($gertans, $key) {
                $gertans->transform(function ($gertan, $key) {
                    $struktur = $gertan->tanggapan->qls_str ? ' Struktur berupa '.$gertan->tanggapan->qls_str : '';

                    $kedalaman_air = $gertan->tanggapan->qls_dep ? ' dengan kedalaman air tanah sekitar '.$gertan->tanggapan->qls_dep.' meter di bawah permukaan.' : '.';
                    return (object) [
                        'id' => $gertan->idx,
                        'judul' => 'Laporan Tanggapan Gerakan Tanah di '.$gertan->crs_vil.', '.$gertan->crs_rgn.', '.$gertan->crs_cty.', '.$gertan->crs_prv,
                        'pelapor' => $gertan->crs_usr,
                        'updated_at' => $gertan->crs_log,
                        'deskripsi' => 'Gerakan tanah terjadi di '.$gertan->crs_vil.', '.$gertan->crs_rgn.', '.$gertan->crs_cty.', '.$gertan->crs_prv.' pada '.$gertan->crs_dtm->formatLocalized('%A, %d %B %Y').' pukul '.$gertan->crs_dtm->format('H:i:s').' '.$gertan->crs_zon.'. Secara Geografis, lokasi kejadian gerakan tanah terletak pada posisi '.$gertan->crs_lat.' LU dan '.$gertan->crs_lon.' BT.',
                        'kerentanan' => empty($gertan->tanggapan->qls_zkg) ? null : 'Lokasi bencana berada pada Zona Potensi Gerakan Tanah '.str_replace_last(', ',' hingga ',title_case(implode(', ',$gertan->tanggapan->qls_zkg))).'.',
                        'rekomendasi' => empty($gertan->tanggapan->rekomendasi) ? null : nl2br($gertan->tanggapan->rekomendasi->qls_rec)
                    ];
                });
            });

            return $grouped;
        });

        $this->gertans = $gertans;
        $this->grouped = $grouped;

        return $this;
    }

    protected function nonFilteredGertan($request)
    {
        $last = Crs::select('idx','crs_log')->orderBy('idx','desc')->first();
        $page = $request->has('page') ? $request->page : 1;

        $date = strtotime($last->crs_log);

        $gertans = Cache::remember('v1/home/sigertan:'.$page.':'.$date, 10, function() {
            return Crs::has('tanggapan')
                    ->with('tanggapan')
                    ->where('crs_sta','TERBIT')
                    ->whereBetween('crs_lat',[-12, 3])
                    ->whereBetween('crs_lon',[89, 149])
                    ->orderBy('crs_log','desc')
                    ->simplePaginate(10);
        });

        $grouped = Cache::remember('v1/home/sigertan:grouped:'.$page.':'.$date, 10, function() use($gertans) {
            $grouped = $gertans->groupBy('date');
            $grouped->each(function ($gertans, $key) {
                $gertans->transform(function ($gertan, $key) {
                    $struktur = $gertan->tanggapan->qls_str ? ' Struktur berupa '.$gertan->tanggapan->qls_str : '';

                    $kedalaman_air = $gertan->tanggapan->qls_dep ? ' dengan kedalaman air tanah sekitar '.$gertan->tanggapan->qls_dep.' meter di bawah permukaan.' : '.';
                    return (object) [
                        'id' => $gertan->crs_ids,
                        'judul' => 'Laporan Tanggapan Gerakan Tanah di '.$gertan->crs_vil.', '.$gertan->crs_rgn.', '.$gertan->crs_cty.', '.$gertan->crs_prv,
                        'pelapor' => $gertan->crs_usr,
                        'updated_at' => $gertan->crs_log,
                        'deskripsi' => 'Gerakan tanah terjadi di '.$gertan->crs_vil.', '.$gertan->crs_rgn.', '.$gertan->crs_cty.', '.$gertan->crs_prv.' pada '.$gertan->crs_dtm->formatLocalized('%A, %d %B %Y').' pukul '.$gertan->crs_dtm->format('H:i:s').' '.$gertan->crs_zon.'. Secara Geografis, lokasi kejadian gerakan tanah terletak pada posisi '.$gertan->crs_lat.' LU dan '.$gertan->crs_lon.' BT.',
                        'kerentanan' => empty($gertan->tanggapan->qls_zkg) ? null : 'Lokasi bencana berada pada Zona Potensi Gerakan Tanah '.str_replace_last(', ',' hingga ',title_case(implode(', ',$gertan->tanggapan->qls_zkg))).'.',
                        'rekomendasi' => empty($gertan->tanggapan->rekomendasi) ? null : nl2br($gertan->tanggapan->rekomendasi->qls_rec)
                    ];
                });
            });

            return $grouped;
        });

        $this->gertans = $gertans;
        $this->grouped = $grouped;

        return $this;
    }

    public function showGertan(Request $request, $id)
    {
        $crs_id = $request ? $request->id : $id;

        $gertan = Cache::remember('v1/json/show:sigertan:'.$crs_id, 60, function() use($crs_id) {
            return Crs::with('tanggapan')->where('crs_ids',$crs_id)->firstOrFail();
        });

        $pelapukan = $gertan->tanggapan->qls_jtp ? 'Jenis Pelapukan berupa ' . $gertan->tanggapan->qls_jtp . '.' : '';
        $struktur = $gertan->tanggapan->qls_str ? ' Struktur berupa '.$gertan->tanggapan->qls_str : '';

        $kedalaman_air = $gertan->tanggapan->qls_dep ? ' dengan kedalaman air tanah sekitar '.$gertan->tanggapan->qls_dep.' meter di bawah permukaan.' : '.';

        $gertan = (object) [
            'laporan' => (object) [
                'pelapor' => $gertan->tanggapan->anggota[0]->user->vg_nama,
                'anggota' => $gertan->tanggapan->anggota,
                'judul' => 'Laporan Tanggapan Gerakan Tanah di '.$gertan->crs_vil.', '.$gertan->crs_rgn.', '.$gertan->crs_cty.', '.$gertan->crs_prv,
                'updated_at' => $gertan->crs_log,
                'deskripsi' => 'Gerakan tanah terjadi di '.$gertan->crs_vil.', '.$gertan->crs_rgn.', '.$gertan->crs_cty.', '.$gertan->crs_prv.' pada tanggal '.$gertan->crs_dtm->format('Y-m-d').' pukul '.$gertan->crs_dtm->format('H:i:s').' '.$gertan->crs_zon.'. Secara Geografis, lokasi kejadian gerakan tanah terletak pada posisi '.$gertan->crs_lat.' LU dan '.$gertan->crs_lon.' BT.',
                'peta' => empty($gertan->tanggapan->qls_pst) ? null : $gertan->tanggapan->qls_pst,
                'foto_sosialisasi' => $gertan->tanggapan->foto_sosialisasi,
                'foto_kejadian' => $gertan->tanggapan->foto_kejadian,
                'status' => $gertan->tanggapan->qls_zkg
            ],
            'tanggapan' => (object) [
                'tipe' => empty($gertan->tanggapan->qls_tgt) ? 'Belum dilaporkan.' : $gertan->tanggapan->qls_tgt,
                'dampak' => $this->setDampak($gertan)->getDampak() ?: ['Belum ada informasi mengenai dampak dari kejadian gerakan tanah ini.'],
                'kondisi' => (object) [
                    'morfologi' => empty($gertan->tanggapan->qls_sba) ? 'Belum dilaporkan.' : 'Secara umum lokasi gerakan tanah ini merupakan daerah '.strtolower(implode(', ',$gertan->tanggapan->qls_sba)).' yang memiliki kemiringan lereng '.implode(', ',$gertan->tanggapan->qls_mrl),
                    'geologi' => empty($gertan->tanggapan->qls_frm) ? 'Belum dilaporkan.' : 'Berdasarkan peta geologi, lokasi bencana tersusun oleh formasi '.$gertan->tanggapan->qls_frm.'. Jenis Batuan di antaranya adalah '.$gertan->tanggapan->qls_jbt.'. '. $pelapukan . $struktur,
                    'keairan' => empty($gertan->tanggapan->qls_air) ? 'Belum dilaporkan.' : 'Keairan di lokasi gerakan tanah berupa '.implode(', ',$gertan->tanggapan->qls_air).$kedalaman_air,
                    'tata_guna_lahan' => empty($gertan->tanggapan->qls_tgl) ? 'Belum dilaporkan.' : 'Tata Guna Lahan  di lokasi gerakan tanah ini berupa '.implode(', ',$gertan->tanggapan->qls_tgl).'.',
                    'kerentanan' => empty($gertan->tanggapan->qls_zkg) ? 'Belum dilaporkan.' : 'Berdasarkan Peta Potensi Gerakan Tanah yang dikeluarkan Badan Geologi, Pusat Vulkanologi dan Mitigasi Bencana Geologi pada bulan ini, lokasi bencana berada pada Zona Potensi Gerakan Tanah '.str_replace_last(', ',' hingga ',title_case(implode(', ',$gertan->tanggapan->qls_zkg))).'. Yang artinya daerah ini memiliki potensi '.strtolower(str_replace_last(', ',' hingga ',implode(', ',$gertan->tanggapan->qls_zkg))).' untuk terjadi gerakan tanah.',
                    'penyebab' => empty($gertan->tanggapan->qls_cau) ? 'Belum dilaporkan.' : title_case(implode('<br>',$gertan->tanggapan->qls_cau))
                ]
            ],
            'rekomendasi' => empty($gertan->tanggapan->rekomendasi) ? 'Belum ada rekomendasi.' : nl2br($gertan->tanggapan->rekomendasi->qls_rec)
        ];

        // return collect($gertan);
        return view('v1.home.sigertan-show', compact('gertan'));
    }

    public function indexGertan(Request $request, $q = null)
    {
        $q == 'q' ? $this->filteredGertan($request)
                    : $this->nonFilteredGertan($request);

        $gertans = $this->getGertans();
        $grouped = $this->getGrouped();

        return view('v1.home.sigertan', compact('gertans','grouped'));
    }
}
