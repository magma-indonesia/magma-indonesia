<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\User;
use App\UserBidang;
use App\UserAdministratif;
use App\Absensi;
use App\Gadd;
use App\MagmaVar;
use App\MagmaVen;
use App\VarVisual;
use App\VarKlimatologi;
use App\VarGempa;
use App\VarRekomendasi;
use App\VarDaily;
use App\Vona;
use App\VonaSubscriber;
use App\SigertanCrs;
use App\MagmaSigertan;
use App\MagmaRoq;
use App\Pengajuan;
use App\v1\MagmaVar as OldVar;

class ImportController extends Controller
{

    /**
     * Index import
     *
     * @return void
     */
    public function index()
    {

        $gempa = new VarGempa();

        $counts = new \stdClass();
        $counts->users = User::count();
        $counts->bidang = UserAdministratif::count();
        $counts->gadds = Gadd::count();
        $counts->vars_old = OldVar::count();
        $counts->vars = MagmaVar::count();
        $counts->var_daily = VarDaily::count();
        $counts->visuals = VarVisual::count();
        $counts->klimatologi = VarKlimatologi::count();
        $counts->gempa = $gempa->jumlah();
        $counts->crs = SigertanCrs::count();
        $counts->vona = Vona::count();
        $counts->subs = VonaSubscriber::count();
        $counts->vens = MagmaVen::count();
        $counts->roq =  MagmaRoq::count();
        $counts->absensi = Absensi::count();
        $counts->sigertan = MagmaSigertan::count();
        $counts->rekomendasi = VarRekomendasi::count();
        $counts->pengajuan = Pengajuan::count();
        
        return view('import.index',compact('counts'));
    }
}
