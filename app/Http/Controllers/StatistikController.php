<?php

namespace App\Http\Controllers;

use App\MagmaVar;
use App\MagmaRoq;
use App\EqLts;
use App\VarGempa;
use App\Vona;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StatistikController extends Controller
{

    protected function dataGempa($year,$month)
    {
        return [
            'total_kejadian' => MagmaRoq::where('utc','like',$year.'-'.$month.'%')
                                    ->count(),
            'total_tanggapan' => MagmaRoq::whereHas('tanggapan')
                                    ->where('utc','like',$year.'-'.$month.'%')
                                    ->count()
        ];
    }

    protected function dataGerakanTanah($year,$month)
    {
        $gertans = \App\SigertanKerusakan::where('noticenumber_id','like','%'.$year.$month.'%')->get();

        return [
            'meninggal' => $gertans->sum('meninggal'),
            'luka_luka' => $gertans->sum('luka'),
            'rumah_rusak' => $gertans->sum('rumah_rusak'),
            'rumah_hancur' => $gertans->sum('rumah_hancur'),
            'rumah_terancam' => $gertans->sum('rumah_terancam'),
            'bangunan_rusak' => $gertans->sum('bangunan_rusak'),
            'bangunan_hancur' => $gertans->sum('bangunan_hancur'),
            'bangunan_terancam' => $gertans->sum('bangunan_terancam'),
            'lahan_rusak' => $gertans->sum('lahan_rusak'),
            'jalan_rusak' => $gertans->sum('jalan_rusak'),
        ];
    }

    protected function dataGunungApi($year,$month)
    {
        $sum = \App\EqApg::where('noticenumber_id','like','%'.$year.$month.'%')->sum('jumlah');
        $sum += \App\EqApl::where('noticenumber_id','like','%'.$year.$month.'%')->sum('jumlah');
        $sum += \App\EqDev::where('noticenumber_id','like','%'.$year.$month.'%')->sum('jumlah');
        $sum += \App\EqDpt::where('noticenumber_id','like','%'.$year.$month.'%')->sum('jumlah');
        $sum += \App\EqGtb::where('noticenumber_id','like','%'.$year.$month.'%')->sum('jumlah');
        $sum += \App\EqGug::where('noticenumber_id','like','%'.$year.$month.'%')->sum('jumlah');
        $sum += \App\EqHbs::where('noticenumber_id','like','%'.$year.$month.'%')->sum('jumlah');
        $sum += \App\EqHrm::where('noticenumber_id','like','%'.$year.$month.'%')->sum('jumlah');
        $sum += \App\EqHyb::where('noticenumber_id','like','%'.$year.$month.'%')->sum('jumlah');
        $sum += \App\EqLof::where('noticenumber_id','like','%'.$year.$month.'%')->sum('jumlah');
        $sum += \App\EqLts::where('noticenumber_id','like','%'.$year.$month.'%')->sum('jumlah');
        $sum += \App\EqMtr::where('noticenumber_id','like','%'.$year.$month.'%')->sum('jumlah');
        $sum += \App\EqTej::where('noticenumber_id','like','%'.$year.$month.'%')->sum('jumlah');
        $sum += \App\EqTel::where('noticenumber_id','like','%'.$year.$month.'%')->sum('jumlah');
        $sum += \App\EqTor::where('noticenumber_id','like','%'.$year.$month.'%')->sum('jumlah');
        $sum += \App\EqTre::where('noticenumber_id','like','%'.$year.$month.'%')->sum('jumlah');
        $sum += \App\EqTrs::where('noticenumber_id','like','%'.$year.$month.'%')->sum('jumlah');
        $sum += \App\EqVlp::where('noticenumber_id','like','%'.$year.$month.'%')->sum('jumlah');
        $sum += \App\EqVta::where('noticenumber_id','like','%'.$year.$month.'%')->sum('jumlah');
        $sum += \App\EqVtb::where('noticenumber_id','like','%'.$year.$month.'%')->sum('jumlah');

        $vona = Vona::where('sent',1)
                        ->where('issued','like',$year.'-'.$month.'%')
                        ->count();

        $var = MagmaVar::where('noticenumber','like','%'.$year.$month.'%')
                    ->count();

        $lts = EqLts::where('noticenumber_id','like','%'.$year.$month.'%')
                    ->get()
                    ->sum('jumlah');

        return [
            'jumlah_laporan' => $var,
            'jumlah_kegempaan' => $sum,
            'jumlah_gempa_letusan' => $lts,
            'jumlah_vona' => $vona,
        ];
    }

    public function index($year = null)
    {

        $year = $year ?: now()->format('Y');

        for ($i=0; $i <12 ; $i++) { 
            $month = $i+1 <= 10 ? '0'.$i : $i;
            $data[$i] = [
                'gempa_bumi' => $this->dataGempa($year,$month),
                'gerakan_tanah' => $this->dataGerakanTanah($year,$month),
                'gunung_api' => $this->dataGunungApi($year,$month),
            ];
        }

        return $data;

    }
}
