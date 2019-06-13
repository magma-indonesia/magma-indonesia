<?php

namespace App\Http\Controllers\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\v1\MagmaVen;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;

class MagmaVenController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $last = MagmaVen::select('erupt_id')->orderBy('erupt_id','desc')->first();
        $page = $request->has('page') ? $request->page : 1;
        $code = $request->has('code') ? $request->code : false;

        if ($code) {
            $vens = Cache::remember('v1/vens:'.$code.':'.$last->erupt_id.'-page-'.$page, 120, function() use($code) {
                return MagmaVen::where('ga_code', $code)->orderBy('erupt_tsp','desc')->paginate(30);
            });
            
        } else {
            $vens = Cache::remember('v1/vens-'.$last->erupt_id.'-page-'.$page, 120, function() {
                return MagmaVen::orderBy('erupt_tsp','desc')->paginate(30);
            });
        }

        return view('v1.gunungapi.ven.index',compact('vens'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $ven = MagmaVen::findOrFail($id);
        $visual = $ven->erupt_vis == '1' 
                    ? $this->visualTeramati($ven)
                    : $this->visualTidakTeramati($ven);

        return view('v1.gunungapi.ven.show',compact('ven','visual'));
    }

    /**
     * Visual letusan teramati
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\MagmaVen $ven
     * @return void
     */
    protected function visualTeramati($ven)
    {
        $asl = $ven->erupt_tka+$ven->gunungapi->ga_elev_gapi;

        $wasap = !empty($ven->erupt_wrn) 
            ? str_replace_last(', ',' hingga ', strtolower(implode(', ',$ven->erupt_wrn))) 
            : strtolower($ven->erupt_wrn[0]);

        $intensitas = !empty($ven->erupt_int)
            ? str_replace_last(', ',' hingga ', strtolower(implode(', ',$ven->erupt_int))) 
            : strtolower($ven->erupt_int[0]);

        $arah = !empty($ven->erupt_arh)
            ? str_replace_last(', ',' dan ', strtolower(implode(', ',$ven->erupt_arh))) 
            : strtolower($ven->erupt_arh[0]);

        $seismik = $ven->erupt_amp ? 'Erupsi ini terekam di seismograf dengan amplitudo maksimum '.$ven->erupt_amp.' mm dan durasi '.$ven->erupt_drs.' detik.' : '';        

        $data = 'Telah terjadi erupsi G. '. $ven->gunungapi->ga_nama_gapi .', '. $ven->gunungapi->ga_prov_gapi .' pada hari '. Carbon::createFromFormat('Y-m-d', $ven->erupt_tgl)->formatLocalized('%A, %d %B %Y') .', pukul '. $ven->erupt_jam.' '.$ven->gunungapi->ga_zonearea.' dengan tinggi kolom abu teramati &plusmn; '. $ven->erupt_tka .' m di atas puncak (&plusmn; '. $asl .' m di atas permukaan laut). Kolom abu teramati berwarna '. $wasap .' dengan intensitas '. $intensitas .' ke arah '. $arah .'. '.$seismik;

        return $data;
    }

    /**
     * visualTidakTeramati
     *
     * @param \App\MagmaVen $ven
     * @return void
     */
    protected function visualTidakTeramati($ven)
    {
        $data = 'Telah terjadi erupsi G. '. $ven->gunungapi->ga_nama_gapi .', '. $ven->gunungapi->ga_prov_gapi .' pada hari '. Carbon::createFromFormat('Y-m-d', $ven->erupt_tgl)->formatLocalized('%A, %d %B %Y') .', pukul '. $ven->erupt_jam.' '.$ven->gunungapi->ga_zonearea.'. Visual letusan tidak teramati. Erupsi ini terekam di seismograf dengan amplitudo maksimum '.$ven->erupt_amp.' mm dan durasi '.$ven->erupt_drs.' detik.';

        return $data;
    }

}
