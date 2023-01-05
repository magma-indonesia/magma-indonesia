<?php

namespace App\Http\Controllers\FrontPage\v1;

use App\GerakanTanah\LewsData;
use App\GerakanTanah\LewsStation;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class LewsController extends Controller
{
    protected function categories()
    {

    }

    public function index(Request $request)
    {
        // return view('v1.home.lews-index');

        // return $stations = LewsStation::all();

        // $data = new LewsData;
        // $data->setTable('TLR0301002');

        // $data->limit(10)->get();

        return view('v1.home.lews-index', [
            'stations' => LewsStation::all(),
            'categories' => [],
            'series' => [],
        ]);
    }
}
