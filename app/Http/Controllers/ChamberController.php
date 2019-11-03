<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ChamberController extends Controller
{
    public function index()
    {

        $vars = \App\MagmaVar::count();
        return view('chambers.index', compact('vars'));

    }
}
