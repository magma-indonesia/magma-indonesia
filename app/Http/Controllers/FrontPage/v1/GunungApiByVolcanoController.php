<?php

namespace App\Http\Controllers\FrontPage\v1;

use App\DataDasarGeologi;
use App\HomeKrb;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\KrbGunungApi;
use App\v1\Gadd;
use App\v1\MagmaVar;
use App\v1\MagmaVen;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class GunungApiByVolcanoController extends Controller
{
    protected function cacheHomeKrb()
    {
        return Cache::rememberForever('home:krb', function () {
            return HomeKrb::latest()->first();
        });
    }

    protected function filteredVen($code, $ven, $page)
    {
        $vens = Cache::remember('v1/home/vens:filtered:' . $ven->erupt_id . ':' . $page . ':' . $code, 120, function () use ($code) {
            return MagmaVen::with('gunungapi:ga_code,ga_nama_gapi,ga_zonearea,ga_elev_gapi', 'user:vg_nip,vg_nama')
                ->where('ga_code', $code)
                ->orderBy('erupt_tgl', 'desc')
                ->orderBy('erupt_jam', 'desc')
                ->paginate(15);
        });

        $vens = $vens->isEmpty() ? collect([]) : $vens;
        return $vens;
    }

    protected function sixHours($vars)
    {
        return $vars->where('var_perwkt', '6')->all();
    }

    protected function daily($vars)
    {
        return $vars->where('var_perwkt', '24');
    }

    protected function convertKrbCode(string $lcode): int
    {
        switch (substr($lcode, 2, 2)) {
            case '03':
                return 3;
            case '02':
                return 2;
            default:
                return 1;
        }
    }

    protected function pendahuluanIndonesia(Gadd $gadd): string
    {
        return $gadd->krbGunungApi->indonesia;
    }

    protected function pendahuluanEnglish(Gadd $gadd): ?string
    {
        return $gadd->krbGunungApi->english ?? null;
    }

    public function varsDate(int $days = 90)
    {
        $end = now();
        $start = now()->subDays($days);

        return "{$start->formatLocalized('%d %B %Y')} - {$end->formatLocalized('%d %B %Y')}";
    }

    public function textKrb(int $zonaKrb): string
    {
        switch ($zonaKrb) {
            case 1:
                return 'KRB 1';
            case 2:
                return 'KRB 2';
            default:
                return 'KRB 3';
        }
    }

    public function longTextKrb(int $zonaKrb): string
    {
        switch ($zonaKrb) {
            case 1:
                return 'Kawasan Rawan Bencana 1';
            case 2:
                return 'Kawasan Rawan Bencana 2';
            default:
                return 'Kawasan Rawan Bencana 3';
        }
    }

    public function krb(KrbGunungApi $krbGunungApi): Collection
    {
        return $krbGunungApi->penjelasans->map(function ($penjelasan) {
            return [
                'href' => "#krb-{$penjelasan->zona_krb}",
                'id' => "krb-{$penjelasan->zona_krb}",
                'text' => $this->textKrb($penjelasan->zona_krb),
                'long_text' => $this->longTextKrb($penjelasan->zona_krb),
                'indonesia' => $penjelasan->indonesia,
                'english' => $penjelasan->english,
                'area_id' => $penjelasan->area_id,
                'area_en' => $penjelasan->area_en,
                'radius' => $penjelasan->radius,
                'radius_id' => $penjelasan->radius_id,
                'radius_en' => $penjelasan->radius_en,
            ];
        });
    }

    public function geologis(DataDasarGeologi $dataDasarGeologi): Collection
    {
        return collect([
            'umum' => 'Umum',
            'morfologi' => 'Morfologi',
            'stratigrafi' => 'Stratigrafi',
            'struktur_geologi' => 'Struktur Geologi',
            'petrografi' => 'Petrografi',
        ])->transform(function ($category, $key) use ($dataDasarGeologi) {
            return [
                'id' => $key,
                'header' => $category,
                'content' => $dataDasarGeologi->{$key},
            ];
        })->values();
    }

    public function intro(Gadd $gadd): string
    {
        return "Gunung Api {$gadd->ga_nama_gapi} terletak di Kab\Kota {$gadd->ga_kab_gapi}, {$gadd->ga_prov_gapi} dengan posisi geografis di Latitude {$gadd->ga_lat_gapi}°LU, Longitude {$gadd->ga_lon_gapi}°BT dan memiliki ketinggian {$gadd->ga_elev_gapi} mdpl.";
    }

    public function show(string $name)
    {
        $home_krb = $this->cacheHomeKrb();

        $gadd = Cache::rememberForever("laporan:{$name}", function () use ($name) {
            return Gadd::where('slug', $name)->with([
                'krbGunungApi',
                'krbGunungApi.penjelasans',
                'krbGunungApi.indexMaps',
                'dataDasarGeologi',
                'dataDasarSejarahLetusan',
                'petaKrbs' => function ($query) {
                    $query->where('published', 1);
                },
            ])->firstOrFail();
        });

        $gadd = Gadd::where('slug', $name)->with([
                'krbGunungApi',
                'krbGunungApi.penjelasans',
                'krbGunungApi.indexMaps',
                'dataDasarGeologi',
                'dataDasarSejarahLetusan',
                'petaKrbs' => function ($query) {
                    $query->where('published', 1);
                },
            ])->firstOrFail();

        $ven = MagmaVen::select('erupt_id')
            ->orderBy('erupt_id', 'desc')
            ->first();

        $vars = MagmaVar::select('no','ga_code', 'periode','ga_nama_gapi', 'var_perwkt', 'var_data_date','cu_status','var_image')
            ->with('gunungapi:ga_code,ga_zonearea')
            ->where('ga_code', $gadd->ga_code)
            ->whereBetween('var_data_date', ['2021-07-01', '2021-07-08'])
            ->orderBy('var_data_date', 'desc')
            ->orderBy('no', 'desc')
            ->get();

        $vars_daily = $this->daily($vars);

        $vens = $this->filteredVen($gadd->ga_code, $ven, 1);

        // return [
        //     'gadd' => $gadd,
        //     'krbs' => $this->krb($gadd->krbGunungApi),
        //     'tentang_id' => $this->pendahuluanIndonesia($gadd),
        //     'tentang_en' => $this->pendahuluanEnglish($gadd),
        //     'home_krb' => $home_krb,
        //     'vars_daily' => $vars_daily->all(),
        //     'var' => $vars_daily->first(),
        //     'activity_date' => $this->varsDate(),
        //     'geologis' => $this->geologis($gadd->dataDasarGeologi),
        // ];

        return view('v1.home.volcano-show', [
            'gadd' => $gadd,
            'intro' => $this->intro($gadd),
            'krbs' => $this->krb($gadd->krbGunungApi),
            'tentang_id' => $this->pendahuluanIndonesia($gadd),
            'tentang_en' => $this->pendahuluanEnglish($gadd),
            'home_krb' => $home_krb,
            'vars_daily' => $vars_daily->all(),
            'var' => $vars_daily->first(),
            'activity_date' => $this->varsDate(),
            'geologis' => $this->geologis($gadd->dataDasarGeologi),
        ]);
    }
}
