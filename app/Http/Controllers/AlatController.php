<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Alat;

class AlatController extends Controller
{

    public function __construct()
    {
        $this->middleware('role:Super Admin')->only('destroy');
    }

    public function index()
    {
        $alats = Alat::with('jenis:id,jenis_alat','gunungapi:code,name,latitude,longitude')
                    ->orderBy('code_id')
                    ->get();

        return view('gunungapi.peralatan.index', compact('alats'));
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
}
