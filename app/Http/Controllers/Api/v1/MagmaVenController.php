<?php

namespace App\Http\Controllers\api\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\v1\MagmaVen;
use Illuminate\Support\Facades\URL;
use App\Http\Resources\v1\MagmaVenCollection;

class MagmaVenController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $vens = MagmaVen::with('gunungapi:ga_code,ga_nama_gapi,ga_elev_gapi')
                    ->orderBy('erupt_tgl','desc')
                    ->orderBy('erupt_jam','desc')
                    ->paginate(5);

        return new MagmaVenCollection($vens);
    }

    public function latest()
    {
        $ven = MagmaVen::with('gunungapi:ga_code,ga_nama_gapi,ga_zonearea')
                ->orderBy('erupt_id','desc')
                ->first();

        return [
            'gunungapi' => $ven->gunungapi->ga_nama_gapi,
            'datetime' => $ven->erupt_tgl.' '.$ven->erupt_jam,
            'deskripsi' => 'Terjadi erupsi G. '.$ven->gunungapi->ga_nama_gapi.' pada hari '.\Carbon\Carbon::createFromFormat('Y-m-d', $ven->erupt_tgl)->formatLocalized('%A, %d %B %Y').' pukul '.$ven->erupt_jam.' '.$ven->gunungapi->ga_zonearea.'.',
            'url' => URL::signedRoute('v1.gunungapi.ven.show', $ven)
        ];
    }
}
