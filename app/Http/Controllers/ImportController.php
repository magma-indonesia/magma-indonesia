<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\User;
use App\UserBidang;
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

        $users = User::count();
        $bidang = UserBidang::count();
        $gadds = Gadd::count();
        $varsv1 = OldVar::count();
        $vars = MagmaVar::count();
        $vardailies = VarDaily::count();
        $visuals = VarVisual::count();
        $klimatologis = VarKlimatologi::count();
        $gempa = new VarGempa();
        $gempa = $gempa->jumlah();
        $crs = SigertanCrs::count();
        $vona = Vona::count();
        $subs = VonaSubscriber::count();
        $vens = MagmaVen::count();
        $roq =  MagmaRoq::count();
        $absensi = Absensi::count();
        $sigertan = MagmaSigertan::count();
        $rekomendasi = VarRekomendasi::count();
        
        return view('import.index',compact(
            'users',
            'bidang',
            'gadds',
            'varsv1',
            'vars',
            'vardailies',
            'visuals',
            'klimatologis',
            'gempa',
            'crs',
            'vona',
            'subs',
            'vens',
            'roq',
            'absensi',
            'sigertan',
            'rekomendasi'
            )
        );
    }
}
