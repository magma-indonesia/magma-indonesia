<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Alat;
use App\Gadd;

class AlatController extends Controller
{

    public function __construct()
    {
        $this->middleware('role:Super Admin')->only('destroy');
    }

    public function index()
    {
        $gadds = Gadd::has('alat')
                    ->with('alat')
                    ->orderBy('name')
                    ->get();

        return view('gunungapi.peralatan.index', compact('gadds'));
    }

    public function create()
    {
        return 'create';
    }

    public function store()
    {
        return 'store';
    }

    public function destroy($alat)
    {
        return 'destroy';
    }

    public function jenis()
    {
        return 'tambah jenis alat';
    }
}
