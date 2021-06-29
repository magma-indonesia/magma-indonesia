<?php

namespace App\Http\Controllers;

use App\Jobs\SendVonaJob;
use App\Mail\VonaSend;
use App\v1\Vona;
use App\VonaSubscriber;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Stevebauman\Location\Facades\Location;

class TestController extends Controller
{
    public function index(Request $request)
    {
        // return $request->ip();
        return dd(Location::get('103.87.160.58'));

        $vona = Vona::first();

        return new VonaSend($vona);

        dispatch(new SendVonaJob($vona));

        return 'oke';
        // phpinfo();
        // return dd($_SERVER, $request->header(), $request->getClientIps() );
    }
}
