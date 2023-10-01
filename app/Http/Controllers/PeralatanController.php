<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PeralatanController extends Controller
{
    public function index()
    {
        return view('peralatan.index');
    }
}
