<?php

namespace App\Http\Controllers\v1\Json;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\KrbGunungApiPenjelasan;
use App\v1\Gadd;
use Illuminate\Support\Facades\Cache;

class GunungApiByVolcanoController extends Controller
{
    public function convertKrbCode(string $lcode): int
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

    public function zonaKrbToText(string $lcode)
    {

    }

    public function content(KrbGunungApiPenjelasan $penjelasan)
    {
        return [
            'indonesia' => nl2br($penjelasan->indonesia),
            'english' => nl2br($penjelasan->english),
            'area_id' => $penjelasan->area_id ?? null,
            'areaen' => $penjelasan->area_en ?? null,
            'radius' => $penjelasan->radius ?? null,
            'radius_id' => $penjelasan->radius_id ?? null,
            'radius_en' => $penjelasan->radius_en ?? null,
        ];
    }

    public function show(Request $request, string $name)
    {
        $gadd = Cache::rememberForever("api:laporan:{$name}", function () use ($name) {
            return Gadd::where('slug', $name)->with([
                'krbGunungApi.penjelasans',
                'krbGunungApi.indexMaps'
            ])->firstOrFail();
        });

        $penjelasan = $gadd->krbGunungApi->penjelasans
            ->where('zona_krb', $this->convertKrbCode($request->lcode))
            ->first();

        return $this->content($penjelasan);

        return [
            'request' => $request->toArray(),
            'name' => $name,
        ];
    }
}
