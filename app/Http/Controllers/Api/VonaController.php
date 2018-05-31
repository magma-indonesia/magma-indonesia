<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Vona;
use App\VonaSubscriber as Subscription;
use App\Gadd;
use App\User;
use Illuminate\Http\Request;
use Carbon\Carbon;

use App\Http\Resources\VonaResource;
use App\Http\Resources\VonaCollection;

class VonaController extends Controller
{
    /**
     * Convert longitude and latitude to decimal text
     *
     * @return lat,lon string
     */
    protected function location($type,$decimal)
    {
        $vars = explode(".",$decimal);
        $deg = $vars[0];
        $tempma = "0.".$vars[1];
        $tempma = $tempma * 3600;
        $min = floor($tempma / 60);
        $sec = round($tempma - ($min*60));

        $sym = "N ";
        if ($deg<10 AND $deg>0){
            $deg="0".$deg;
        } else {
            if ($deg<0){
                $deg="0".abs($deg);
                $sym="S ";
            }
        }
        if ($min<10){
            $min="0".$min;
        }
        if ($sec<10){
            $sec="0".$sec;
        }

        if ($type == 'lat')
        {
            return $sym.$deg.' deg '.$min.' min '.$sec.' sec '; 
        }
        return 'E '.$deg.' deg '.$min.' min '.$sec.' sec'; 
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {        
        $vonas = Vona::orderBy('issued','desc')->where('sent',1)->paginate(30,['*'],'vona_page');
        return new VonaCollection($vonas);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Vona  $vona
     * @return \Illuminate\Http\Response
     */
    public function show($uuid)
    {
        $vona = Vona::findOrFail($uuid);
        return new VonaResource($vona);
    }

}
