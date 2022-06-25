<?php

namespace App\Http\Controllers\FrontPage\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\KameraGunungApi;
use Karlmonson\Ping\Facades\Ping;
use Intervention\Image\Facades\Image;

class KameraGunungApiController extends Controller
{
    protected $groups = [
        'Bali' => [
            'AGU',
            'BAT',
        ],
        'Jawa' => [
            'KRA',
            'WEL',
            'BRO',
            'CER',
            'DIE',
            'GAL',
            'GED',
            'GUN',
            'IJE',
            'KLD',
            'LAM',
            'MER',
            'PAP',
            'RAU',
            'SAL',
            'SMR',
            'SLA',
            'SBG',
            'SUN',
            'TPR',
            'BAN',
        ],
        'Maluku' => [
            'DUK',
            'GML',
            'GMK',
            'IBU',
            'KIE',
            'NIE',
            'NLA',
            'SRA',
            'TEO',
            'WTR',
            'WUR',
        ],
        'Nusa Tenggara' => [
            'RAN',
            'TAR',
            'EBU',
            'EGO',
            'HOB',
            'BOL',
            'LEW',
            'WER',
            'LIK',
            'RIE',
            'IYA',
            'KLM',
            'LER',
            'LWK',
            'LWP',
            'RIN',
            'ROK',
            'SAN',
            'SIR',
            'TAM',
        ],
        'Sulawesi' => [
            'AMB',
            'AWU',
            'BWU',
            'COL',
            'KAR',
            'LOK',
            'MAH',
            'RUA',
            'SGR',
            'SOP',
            'TGK',
        ],
        'Sumatera' => [
            'TEL',
            'DEM',
            'KAB',
            'KER',
            'MAR',
            'PEU',
            'SEU',
            'SIN',
            'SOR',
            'TAL',
            'TAN',
        ],
    ];

    protected $regions = [
        'bali' => 'Bali',
        'jawa' => 'Jawa',
        'maluku' => 'Maluku',
        'nusa-tenggara' => 'Nusa Tenggara',
        'sulawesi' => 'Sulawesi',
        'sumatera' => 'Sumatera',
    ];

    protected function groupedCctv($cctvs)
    {
        $groups = collect($this->groups);
        $groups->transform(function ($codes) use ($cctvs) {
            return collect($cctvs)->whereIn('code', $codes)->values();
        });

        return $groups;
    }

    protected function filteredCCTV($code)
    {
        return KameraGunungApi::with('gunungapi:code,name')
                ->where('publish',1)
                ->where('code',$code)
                ->orderBy('code')
                ->get();
    }

    protected function nonFilteredCCTV($request)
    {
        if ($request->has('region') && array_key_exists(strtolower($request->region), $this->regions)) {
            $codes = $this->groups[$this->regions[strtolower($request->region)]];

            return KameraGunungApi::with('gunungapi:code,name')
                ->where('publish', 1)
                ->whereIn('code', $codes)
                ->orderBy('code')
                ->get();
        };

        return KameraGunungApi::with('gunungapi:code,name')
                ->where('publish',1)
                ->orderBy('code')
                ->get();
    }

    public function index(Request $request)
    {
        $health = Ping::check(config('app.cctv_url'));

        if ($health == 200)
        {
            $cctvs = $this->nonFilteredCCTV($request);

            if ($cctvs->isEmpty())
                abort(404);

            $cctvs = $cctvs->each(function ($item, $key) {
                try {
                    $image = Image::make($item->full_url)
                                ->widen(150)->stream('data-url');
                    $item['image'] = $image;
                }

                catch(\Exception $e) {
                    $item['image'] = null;
                }
            })->reject(function ($cctv) {
                return is_null($cctv['image']);
            });

            $regions = $this->regions;

            $gadds = $cctvs->groupBy('gunungapi.name');

            $grouped = $this->groupedCctv($cctvs);

            return view('v1.home.cctv',compact('regions', 'grouped', 'gadds'));
        }

        abort(500, 'Server CCTV sedang Off');
    }

    public function filter(Request $request, $code)
    {
        $cctvs = $this->filteredCCTV($code);

        if ($cctvs->isEmpty())
            abort(404);

        $cctvs = $cctvs->each(function ($item, $key) {
            try {
                $image = Image::make($item->full_url)
                    ->widen(150)->stream('data-url');
                $item['image'] = $image;
            } catch (\Exception $e) {
                $item['image'] = null;
            }
        })->reject(function ($cctv) {
            return is_null($cctv['image']);
        });

        $regions = $this->regions;

        $gadds = $cctvs->groupBy('gunungapi.name');

        $grouped = $this->groupedCctv($cctvs);

        return view('v1.home.cctv', compact('regions', 'grouped', 'gadds'));
    }

    public function show(Request $request)
    {
        $health = Ping::check(config('app.cctv_url'));

        if ($health == 200)
        {
            $cctv = KameraGunungApi::with('gunungapi:code,name')
                        ->where('publish',1)
                        ->where('uuid',$request->uuid)
                        ->firstOrFail();

            try {
                $image = Image::make($cctv->full_url)->stream('data-url');
                $cctv->setAttribute('image', $image);
            }

            catch (\Exception $e) {
                $cctv->setAttribute('image', null);
            }

            $cctv->increment('hit');

            return view('v1.home.cctv-show',compact('cctv'));

        }

        abort(500, 'Server CCTV sedang Off');
    }
}
