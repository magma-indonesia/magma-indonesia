<?php

namespace App\Http\Controllers;

use App\Jobs\SendVonaJob;
use App\Mail\VonaSend;
use App\v1\Vona;
use App\VonaSubscriber;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class TestController extends Controller
{
    public function index(Request $request)
    {

        $vona = Vona::first();

        return new VonaSend($vona);

        dispatch(new SendVonaJob($vona));

        return 'oke';
        // phpinfo();
        // return dd($_SERVER, $request->header(), $request->getClientIps() );
    }
}
