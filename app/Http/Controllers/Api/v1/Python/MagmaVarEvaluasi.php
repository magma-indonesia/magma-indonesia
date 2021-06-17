<?php

namespace App\Http\Controllers\Api\v1\Python;

use App\Http\Controllers\Controller;
use App\Http\Requests\v1\Api\MagmaVarEvaluasiRequest;
use App\Traits\v1\DeskripsiGempa;
use App\v1\Gadd;
use App\v1\MagmaVar;
use Carbon\CarbonPeriod;
use Illuminate\Support\Str;

class MagmaVarEvaluasi extends Controller
{

    use DeskripsiGempa;

    protected $code_request = ['lts', 'apl', 'apg', 'gug', 'hbs', 'tre', 'tor', 'lof', 'hyb', 'vtb', 'vta', 'vlp', 'tel', 'trs', 'tej', 'dev', 'gtb', 'hrm', 'dpt', 'mtr'];

    public function result(MagmaVarEvaluasiRequest $request)
    {
        $volcano = Gadd::select('ga_code', 'ga_nama_gapi')->where('ga_code',$request->code_ga)->first();

        $query = MagmaVar::query()
            ->where('ga_code', $request->code_ga)
            ->where('var_perwkt', '24 Jam')
            ->whereBetween('var_data_date', [$request->start_date, $request->end_date])
            ->orderBy('var_data_date', 'asc');

        $vars = $query->get();

        $gempas = in_array('*', $request->gempa) ? $this->code_request : $request->gempa;

        $vars->transform(function ($var) use ($gempas) {
            $data['date'] = $var->var_data_date->format('Y-m-d');
            $data['availability'] = 1;

            foreach ($gempas as $gempa) {
                $data['gempa'][Str::snake(str_replace('/', ' ', $this->codes[$gempa]))] = $var->{'var_' . $gempa};
            }

            $data['visual'] = $this->visual($var);

            return $data;
        });

        return [
            'volcano' => $volcano->ga_nama_gapi,
            'date_period' => [
                $request->start_date,
                $request->end_date,
            ],
            'missing_data' => $this->missingData($vars, $request),
            'data_count' => $vars->count(),
            'data' => $vars
        ];
    }

    protected function visual($var)
    {
        return [
            'visibility' => $var->var_visibility,
            'cuaca' => $var->var_cuaca,
            'asap' => [
                'teramati' => $var->var_asap == 'Teramati' ? true : false,
                'tinggi_min' => $var->var_tasap_min,
                'tinggi_max' => $var->var_tasap,
                'warna' => $var->var_wasap,
                'intensitas' => $var->var_intasap,
                'tekanan' => $var->var_tekasap,
            ],
            'letusan' => [
                'teramati' => $var->var_lts > 0 ? true : false,
                'tinggi_min' => $var->var_lts_tmin,
                'tinggi_max' => $var->var_lts_tmax,
                'warna' => $var->var_lts_wasap,
            ],
            'awan_panas_guguran' => [
                'teramati' => $var->var_apg > 0 ? true : false,
                'jarak_min' => $var->var_apg_rmin,
                'jarak_max' => $var->var_apg_rmax,
            ],
        ];
    }

    protected function klimatologi()
    {
        return [

        ];
    }

    protected function missingData($vars, $request)
    {
        $pluckDate = $vars->pluck('date');

        $datePeriod = collect(CarbonPeriod::create(
            $request->start_date,
            $request->end_date
        ))->map(function ($date) {
            return $date->format('Y-m-d');
        });

        return $datePeriod->diff($pluckDate)->flatten();
    }

    protected function fillEmptyData($vars, $request)
    {

        $pluckDate = $vars->pluck('date');

        $datePeriod = collect(CarbonPeriod::create(
            $request->start_date,
            $request->end_date
        ))->map(function ($date) {
            return $date->format('Y-m-d');
        });

        $diffs = $datePeriod->diff($pluckDate)->flatten();

        if ($diffs->isNotEmpty()) {

            $gempas = $request->gempa;

            $diffs->transform(function ($diff) use ($gempas) {
                $data['date'] = $diff;
                $data['availability'] = 0;

                foreach ($gempas as $gempa) {
                    $data['gempa'][Str::snake(str_replace('/',' ',$this->codes[$gempa]))] = 0;
                }

                $data['visual'] = [];

                return $data;
            });

            return $vars->concat($diffs);
        }

        return $vars;
    }
}

