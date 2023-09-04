<?php

namespace App\Http\Controllers\FrontPage\v1;

use App\HomeKrb;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\v1\Gadd;
use App\v1\MagmaVar;
use App\v1\MagmaVen;
use Illuminate\Support\Facades\Cache;

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

    public function show(string $name)
    {
        $home_krb = $this->cacheHomeKrb();

        $gadd = Cache::rememberForever("laporan:{$name}", function () use ($name) {
            return Gadd::where('slug', $name)->with([
                'krbGunungApi',
                'krbGunungApi.penjelasans',
                'krbGunungApi.indexMaps'
            ])->firstOrFail();
        });

        $ven = MagmaVen::select('erupt_id')
            ->orderBy('erupt_id', 'desc')
            ->first();

        $vars = MagmaVar::select('no','ga_code', 'var_perwkt', 'var_data_date','cu_status')
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
        //     'home_krb' => $home_krb,
        //     'vars_daily' => $vars_daily->all(),
        //     'var' => $vars_daily->first(),
        // ];

        return view('v1.home.volcano-show', [
            'gadd' => $gadd,
            'tentang_id' => $this->pendahuluanIndonesia($gadd),
            'tentang_en' => $this->pendahuluanEnglish($gadd),
            'home_krb' => $home_krb,
            'vars_daily' => $vars_daily->all(),
            'var' => $vars_daily->first(),
        ]);
    }
}
