<?php

namespace App\Http\Controllers\FrontPage\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Cache;
use Validator;
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
                    ->where('datetime_wib',[$request->start,$request->end])
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
        $last = MagmaRoq::select('no','datetime_wib')
                    ->where('roq_tanggapan','YA')
                    ->orderBy('datetime_wib','desc')
                    ->firstOrFail();

        $page = $request->has('page') ? $request->page : 1;
        $date = strtotime($last->datetime_wib);

        $gempas = Cache::remember('v1/home/roqs:'.$last->no.':'.$page.':'.$date, 120, function () {
            return MagmaRoq::where('roq_tanggapan','YA')
                    ->orderBy('datetime_wib','desc')
                    ->simplePaginate(10);
        });

        $grouped = Cache::remember('v1/home/roqs:grouped:'.$last->no.':'.$page.':'.$date, 120, function () use ($gempas) {
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
        return 'Coming sooonnnnnnnn';
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
