<?php

namespace App\Http\Controllers\Api\v1\Python;

use App\Http\Controllers\Controller;
use App\Http\Requests\v1\Api\MagmaVarEvaluasiRequest;
use App\Traits\v1\DeskripsiGempa;
use App\v1\MagmaVar;
use Carbon\CarbonPeriod;
use Illuminate\Support\Str;

class MagmaVarEvaluasi extends Controller
{

    use DeskripsiGempa;

    public function result(MagmaVarEvaluasiRequest $request)
    {
        $query = MagmaVar::select('ga_code', 'var_data_date', 'var_perwkt')
            ->where('ga_code', 'SIN')
            ->where('var_perwkt', '24 Jam')
            ->whereBetween('var_data_date', [$request->start_date, $request->end_date])
            ->orderBy('var_data_date', 'asc');

        $gempas = $request->gempa;

        foreach ($request->gempa as $gempa) {
            $query->addSelect('var_' . $gempa);
        }

        $vars = $query->get();

        $vars->transform(function ($var) use ($gempas) {
            $data['date'] = $var->var_data_date->format('Y-m-d');
            $data['availability'] = 1;

            foreach ($gempas as $gempa) {
                $data[Str::snake($this->codes[$gempa])] = $var->{'var_' . $gempa};
            }

            return $data;
        });

        return $this->fillEmptyData($vars, $request);
    }

    protected function fillEmptyData($vars, $request)
    {
        $gempas = $request->gempa;

        $pluckDate = $vars->pluck('date');

        $datePeriod = collect(CarbonPeriod::create(
            $request->start_date,
            $request->end_date
        ))->map(function ($date) {
            return $date->format('Y-m-d');
        });

        $diffs = $datePeriod->diff($pluckDate)->flatten();

        if ($diffs->isNotEmpty()) {
            $diffs->transform(function ($diff) use ($gempas) {
                $data['date'] = $diff;
                $data['availability'] = 0;

                foreach ($gempas as $gempa) {
                    $data[Str::snake($this->codes[$gempa])] = 0;
                }

                return $data;
            });

            return $vars->concat($diffs);
        }

        return $vars;
    }
}

