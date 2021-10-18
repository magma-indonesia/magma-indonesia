<?php

namespace App\Http\Controllers\FrontPage\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;
use App\v1\MagmaRoq;

class GempaBumiController extends Controller
{

    protected $gempas;
    protected $grouped;

    protected function getGempas()
    {
        return $this->gempas;
    }

    protected function getGrouped()
    {
        return $this->grouped;
    }

    protected function filteredGempa($request)
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

        $gempas = Cache::remember('v1/home/roqs:search:'.$page.':'.$start.':'.$end, 120, function () use ($request){
            return MagmaRoq::where('roq_tanggapan','YA')
                    ->whereBetween('datetime_wib',[$request->start,$request->end])
                    ->orderBy('datetime_wib','desc')
                    ->simplePaginate(10);
        });

        $grouped = Cache::remember('v1/home/roqs:search:grouped:'.$page.':'.$start.':'.$end, 120, function () use ($gempas) {
            $grouped = $gempas->groupBy(function ($val) {
                return $val->datetime_wib->format('Y-m-d');
            });

            $grouped->each(function ($gempas, $key) {
                $gempas->transform(function ($roq, $key) {
                    return (object) [
                        'id' => $roq->no,
                        'judul' => $roq->area,
                        'pelapor' => $roq->roq_nama_pelapor ? $roq->roq_nama_pelapor : 'Belum ada.',
                        'waktu' => $roq->datetime_wib,
                        'kota_terdekat' => $roq->koter ?: null,
                        'kedalaman' => $roq->depth.' Km',
                        'magnitude' => $roq->magnitude.' SR',
                        'sumber' => $roq->roq_source,
                        'intensitas' => $roq->mmi ?: null,
                        'tsunami' => (empty($roq->roq_tsu) || $roq->roq_tsu=='TIDAK') ? 'Tidak berpotensi Tsunami' : 'Berpotensi terjadi Tsunami',
                        'pendahuluan' => str_replace('Â°',' ',$roq->roq_intro),
                        'rekomendasi' => nl2br($roq->roq_rekom),
                    ];
                });
            });

            return $grouped;
        });

        $this->gempas = $gempas;
        $this->grouped = $grouped;

        return $this;
    }

    protected function nonFilteredGempa($request)
    {
        $last = MagmaRoq::select('no','datetime_wib','roq_logtime')
                ->where('roq_tanggapan','YA')
                ->orderBy('datetime_wib','desc')
                ->first();

        $page = $request->has('page') ? $request->page : 1;
        $date = strtotime($last->roq_logtime);

        $gempas = Cache::remember('v1/home/roqs:'.$page.':'.$date, 120, function () {
            return MagmaRoq::where('roq_tanggapan','YA')
                    ->orderBy('datetime_wib','desc')
                    ->simplePaginate(10);
        });

        $grouped = Cache::remember('v1/home/roqs:grouped:'.$page.':'.$date, 120, function () use ($gempas) {
            $grouped = $gempas->groupBy(function ($val) {
                return $val->datetime_wib->format('Y-m-d');
            });

            $grouped->each(function ($gempas, $key) {
                $gempas->transform(function ($roq, $key) {
                    return (object) [
                        'id' => $roq->no,
                        'judul' => $roq->area,
                        'pelapor' => $roq->roq_nama_pelapor ? $roq->roq_nama_pelapor : 'Belum ada.',
                        'waktu' => $roq->datetime_wib,
                        'kota_terdekat' => $roq->koter ?: null,
                        'kedalaman' => $roq->depth.' Km',
                        'magnitude' => $roq->magnitude.' SR',
                        'sumber' => $roq->roq_source,
                        'intensitas' => $roq->mmi ?: null,
                        'tsunami' => (empty($roq->roq_tsu) || $roq->roq_tsu=='TIDAK') ? 'Tidak berpotensi Tsunami' : 'Berpotensi terjadi Tsunami',
                        'pendahuluan' => str_replace('Â°',' ',$roq->roq_intro),
                        'rekomendasi' => nl2br($roq->roq_rekom),
                    ];
                });
            });

            return $grouped;
        });

        $this->gempas = $gempas;
        $this->grouped = $grouped;

        return $this;
    }

    public function showGempa($id)
    {
        $roqs = Cache::remember('v1/home/roq:show:'.$id, 60, function() use($id) {
            return MagmaRoq::where('no',$id)->firstOrFail();
        });

        $roq = Cache::remember('v1/home/roq:show:transform:'.$id, 60, function() use($roqs) {
            $roqs = collect([$roqs]);
            $roqs->transform(function ($roq, $key) {
                return (object) [
                    'laporan' => (object) [
                        'title' => $roq->area,
                        'pelapor' => $roq->roq_nama_pelapor ? $roq->roq_nama_pelapor : 'Belum ada.',
                        'waktu' => $roq->datetime_wib,
                        'kota_terdekat' => $roq->koter ? $roq->koter : 'Belum ada data.',
                        'loc' => [$roq->lat_lima,$roq->lon_lima],
                        'latitude' => $roq->lat_lima.'&deg;LU',
                        'longitude' => $roq->lon_lima.'&deg;BT',
                        'kedalaman' => $roq->depth.' Km',
                        'magnitude' => $roq->magnitude.' SR',
                        'gunung_terdekat' => $roq->nearest_volcano ? $roq->nearest_volcano : 'Belum ada data.',
                        'map' => $roq->roq_maplink ? $roq->roq_maplink : '',
                        'sumber' => $roq->roq_source ?: 'Badan Meteorologi, Klimatologi dan Geofisika (BMKG)',
                        'intensitas' => $roq->mmi ?: null,
                        'has_tanggapan' => $roq->roq_tanggapan == 'YA' ? true : false,
                    ],
                    'tanggapan' => (object) [
                        'tsunami' => (empty($roq->roq_tsu) || $roq->roq_tsu=='TIDAK') ? 'Tidak berpotensi Tsunami' : 'Berpotensi terjadi Tsunami',
                        'pendahuluan' => $roq->roq_tanggapan == 'YA' ? str_replace('Â°',' ',$roq->roq_intro) : 'Belum ada tanggapan.',
                        'kondisi' => $roq->roq_tanggapan == 'YA' ? $roq->roq_konwil :  'Belum ada tanggapan.',
                        'mekanisme' => $roq->roq_tanggapan == 'YA' ? $roq->roq_mekanisme :  'Belum ada tanggapan.',
                        'efek' => $roq->roq_tanggapan == 'YA' ? $roq->roq_efek :  'Belum ada tanggapan.',
                    ],
                    'rekomendasi' => $roq->roq_tanggapan == 'YA' ? nl2br($roq->roq_rekom) :  'Belum ada rekomendasi.',
                ];
            });

            return $roqs->first();
        });

        return view('v1.home.gempa-show', compact('roq'));
    }

    public function indexGempa(Request $request, $q = null)
    {
        $q === 'q' ? $this->filteredGempa($request)
                    : $this->nonFilteredGempa($request);

        $gempas = $this->getGempas();
        $grouped = $this->getGrouped();

        return view('v1.home.gempa', compact('gempas','grouped'));

    }
}
