<?php

namespace App\Http\Controllers;

use App\MagmaRoq;
use App\v1\MagmaRoq as OldMagmaRoq;
use Illuminate\Http\Request;

class MagmaRoqController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $roqs = MagmaRoq::orderBy('utc','desc')
            ->paginate(30,['*'],'roq_page');

        return view('gempabumi.index',compact('roqs'));
    }

}
