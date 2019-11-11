<?php

namespace App\Http\Controllers;

use App\MagmaVar;
use App\EqLts;
use Illuminate\Http\Request;

class ChamberController extends Controller
{
    public function index()
    {

        $vars_count = MagmaVar::count();
        $latest = Magmavar::latest()->first();
        $lts_sum = EqLts::sum('jumlah');
        $latest_lts = EqLts::latest()->first();
        return view('chambers.index', compact('vars_count','latest','lts_sum','latest_lts'));

    }
}
