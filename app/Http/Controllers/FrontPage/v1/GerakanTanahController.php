<?php

namespace App\Http\Controllers\FrontPage\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Cache;
use Validator;
use App\v1\GertanCrs as Crs;
use App\v1\MagmaSigertan as Sigertan;
use App\v1\SigertanAnggotaTim as AnggotaTim;
use App\v1\SigertanFotoKejadian as FotoKejadian;
use App\v1\SigertanFotoSosialisasi as FotoSosialisasi;
use App\v1\SigertanRekomendasi as Rekomendasi;
use App\v1\SigertanStatus as Status;
use App\v1\SigertanVerifikator as Verifikator;

class GerakanTanahController extends Controller
{
    protected $dampak;
    protected $gertans;
    protected $grouped;

    protected function setDampak($gertan)
    {
        $dampak = [
            'korban' => $gertan->tanggapan->qls_kmd ? '<p>'.$gertan->tanggapan->qls_kmd.' korban meninggal dunia.</p>' : '',
            
        ];
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

        $this->dampak = $dampak ?: 'Belum ada informasi mengenai dampak dari kejadian gerakan tanah ini.';

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

        $gertans = Cache::remember('v1/home/sigertan:search:'.$page.':'.$start.':'.$end, 120, function() use($request) {
            return Crs::has('tanggapan')
                    ->with('tanggapan')
                    ->where('crs_sta','TERBIT')
                    ->whereBetween('crs_lat',[-12, 2])
                    ->whereBetween('crs_lon',[89, 149])
                    ->whereBetween('crs_dtm',[$request->start,$request->end])
                    ->orderBy('crs_log','desc')
                    ->simplePaginate(10);
        });

        $grouped = Cache::remember('v1/home/sigertan:search:grouped:'.$page.':'.$start.':'.$end, 120, function() use($gertans) {
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
                        'deskripsi' => 'Gerakan tanah terjadi di '.$gertan->crs_vil.', '.$gertan->crs_rgn.', '.$gertan->crs_cty.', '.$gertan->crs_prv.' pada '.$gertan->crs_log->formatLocalized('%A, %d %B %Y').' pukul '.$gertan->crs_log->format('H:i:s').' '.$gertan->crs_zon.'. Secara Geografis, lokasi kejadian gerakan tanah terletak pada posisi '.$gertan->crs_lat.' LU dan '.$gertan->crs_lon.' BT.',
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
        $last = Crs::select('idx')->orderBy('idx','desc')->first();
        $page = $request->has('page') ? $request->page : 1;

        $gertans = Cache::remember('v1/home/sigertan:'.$page, 120, function() {
            return Crs::has('tanggapan')
                    ->with('tanggapan')
                    ->where('crs_sta','TERBIT')
                    ->whereBetween('crs_lat',[-12, 2])
                    ->whereBetween('crs_lon',[89, 149])
                    ->orderBy('crs_log','desc')
                    ->simplePaginate(10);
        });

        $grouped = Cache::remember('v1/home/sigertan:grouped'.$page, 120, function() use($gertans) {
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
                        'deskripsi' => 'Gerakan tanah terjadi di '.$gertan->crs_vil.', '.$gertan->crs_rgn.', '.$gertan->crs_cty.', '.$gertan->crs_prv.' pada '.$gertan->crs_log->formatLocalized('%A, %d %B %Y').' pukul '.$gertan->crs_dtm->format('H:i:s').' '.$gertan->crs_zon.'. Secara Geografis, lokasi kejadian gerakan tanah terletak pada posisi '.$gertan->crs_lat.' LU dan '.$gertan->crs_lon.' BT.',
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

    public function showGertan($id)
    {
        
        return 'Coming Soon';
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
