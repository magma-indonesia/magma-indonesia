<?php

namespace App\Http\Controllers;

use App\MagmaRoq;
use App\v1\MagmaVar;
use App\v1\Vona;
use Illuminate\Support\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Support\Facades\Cache;

class StatistikController extends Controller
{

    // protected function dataGempa($datePeriod)
    // {
    //     return [
    //         'total_kejadian' => MagmaRoq::where('utc','like',$year.'-'.$month.'%')
    //                                 ->count(),
    //         'total_tanggapan' => MagmaRoq::whereHas('tanggapan')
    //                                 ->where('utc','like',$year.'-'.$month.'%')
    //                                 ->count()
    //     ];
    // }

    // protected function dataGerakanTanah($datePeriod)
    // {
    //     return [
    //         'total_laporan' => $gertans,
    //         'total_tanggapan' => $tanggapan,
    //         'dampak' => [
    //             'meninggal' => $dampak->sum('meninggal'),
    //             'luka_luka' => $dampak->sum('luka'),
    //             'rumah_rusak' => $dampak->sum('rumah_rusak'),
    //             'rumah_hancur' => $dampak->sum('rumah_hancur'),
    //             'rumah_terancam' => $dampak->sum('rumah_terancam'),
    //             'bangunan_rusak' => $dampak->sum('bangunan_rusak'),
    //             'bangunan_hancur' => $dampak->sum('bangunan_hancur'),
    //             'bangunan_terancam' => $dampak->sum('bangunan_terancam'),
    //             'lahan_rusak' => $dampak->sum('lahan_rusak'),
    //             'jalan_rusak' => $dampak->sum('jalan_rusak'),
    //         ]
    //     ];
    // }

    protected function data($datePeriod)
    {
        $data = $datePeriod->map(function ($date) {

            $vars = MagmaVar::select('no', 'var_data_date', 'var_perwkt', 'var_vta', 'var_vtb', 'var_lts', 'var_apg')
            ->where('var_perwkt', '24 Jam')
            ->whereBetween('var_data_date', [$date->format('Y-m-d'), $date->endOfMonth()->format('Y-m-d')])
                ->get();

            return [
                'date' => $date->formatLocalized('%B %Y'),
                'jumlah' => [
                    'laporan' => $vars->count(),
                    'gempa_vulkanik' => $vars->sum('var_vta') + $vars->sum('var_vtb'),
                    'gempa_letusan' => $vars->sum('var_lts'),
                    'awan_panas_guguran' => $vars->sum('var_apg'),
                    'gunung_meletus' => $vars->where('var_lts', '>', 0)->count(),
                    'vona' => Vona::whereBetween('issued_time', [$date->startOfMonth()->format('Y-m-d'), $date->endOfMonth()->format('Y-m-d')])->count(),
                ]
            ];
        });

        return $data;
    }

    protected function dataCurrentYear($datePeriod)
    {
        $data = Cache::remember('v1/statistik/' . $datePeriod->first()->format('Y'), 86400, function () use ($datePeriod) {
            return $this->data($datePeriod);
        });

        return $data;
    }

    protected function dataPreviousYear($datePeriod)
    {
        $data = Cache::rememberForever('v1/statistik/' . $datePeriod->first()->format('Y'), function () use ($datePeriod) {
            return $this->data($datePeriod);
        });

        return $data;
    }

    protected function dataGunungApi($datePeriod)
    {
        return $datePeriod->first()->format('Y') == now()->format('Y') ?
            $this->dataCurrentYear($datePeriod) :
            $this->dataPreviousYear($datePeriod);
    }

    public function index($year = null)
    {
        $year = $year == now()->format('Y') ? null : $year;

        $years = collect(CarbonPeriod::create(
            Carbon::createFromDate('2015')->startOfYear(),
            '1 year',
            now()->endOfYear(),
        ));

        $datePeriod = collect(CarbonPeriod::create(
            $year ? Carbon::createFromDate($year)->startOfYear() : now()->startOfYear(),
            '1 month',
            $year ? Carbon::createFromDate($year)->endOfYear() : now()->endOfMonth(),
        ));

        // return [
        //     'gunung_api' => $this->dataGunungApi($datePeriod),
        // ];

        return view('v1.home.statistik', [
            'years' => $years,
            'gunung_apis' => $this->dataGunungApi($datePeriod),
        ]);

    }
}
