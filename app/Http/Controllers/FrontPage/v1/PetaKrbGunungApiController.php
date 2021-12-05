<?php

namespace App\Http\Controllers\FrontPage\v1;

use App\Gadd;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\PetaKrbGunungApi as KRB;
use App\HomeKrb;

class PetaKrbGunungApiController extends Controller
{
    public function index()
    {
        $gadds = Gadd::select('code','name')
            ->has('peta_krbs')
            ->with(['peta_krbs' => function ($query) {
                $query->where('published', 1);
            }])
            ->orderBy('name')
            ->get();

        return view('v1.home.peta-krb', [
            'gadds' => $gadds,
            'home_krbs' => HomeKrb::orderBy('created_at', 'desc')->get()
        ]);
    }
}
