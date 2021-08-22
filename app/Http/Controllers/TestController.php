<?php

namespace App\Http\Controllers;

use App\Jobs\SendVonaJob;
use App\Mail\VonaSend;
use App\v1\MagmaVar;
use App\v1\Vona;
use App\VonaSubscriber;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Stevebauman\Location\Facades\Location;

class TestController extends Controller
{
    public function index(Request $request)
    {
        return DB::connection('magma')->table('magma_var')
            ->selectRaw('DISTINCT ga_nama_gapi, cu_status')
            ->whereBetween('var_data_date', ['2021-07-01', '2021-07-31'])
            ->orderBy('ga_nama_gapi')
            ->get();

        // return MagmaVar::select(DB::raw('DISTINCT(ga_nama_gapi, cu_status)'))
        //         ->whereBetween('var_data_date', [now()->startOfYear()->format('Y-m-d'), now()->endOfMonth()->format('Y-m-d')])
        //         ->get();
        // return $request->ip();
        // return dd(Location::get('103.87.160.58'));

        // $vona = Vona::first();

        // return new VonaSend($vona);

        // dispatch(new SendVonaJob($vona));

        // return 'oke';
        // phpinfo();
        // return dd($_SERVER, $request->header(), $request->getClientIps() );
    }
}
