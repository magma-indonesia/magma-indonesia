<?php

namespace App\Http\Controllers;

use App\Services\VonaService;
use App\v1\MagmaVen;
use Illuminate\Http\Request;

class TestController extends Controller
{
    public function index()
    {
        $vens = MagmaVen::with('gunungapi:ga_code,ga_nama_gapi,ga_zonearea,ga_elev_gapi', 'user:vg_nip,vg_nama')
            ->whereNull('vona_created_at')->get();

        $ven = $vens->first();

        $request = new Request([
            'type' => 'real',
            'code' => $ven->gunungapi->ga_code,
            'color' => 'auto',
            'visibility' => $ven->erupt_vis,
            'height' => $ven->erupt_vis ? $ven->erupt_tka : 0,
            'warna_asap' => $ven->erupt_wrn,
            'intensitas' => $ven->erupt_int,
            'arah_abu' => $ven->erupt_arh,
            'date' => "{$ven->erupt_tgl} {$ven->erupt_jam}",
            'terjadi_gempa_letusan' => $ven->erupt_amp ? 1 : 0,
            'terjadi_tremor' => 0,
            'amplitudo' => $ven->erupsi_berlangsung ? 0 : $ven->erupt_amp,
            'durasi' => $ven->erupsi_berlangsung ? 0 : $ven->erupt_drs,
            'amplitudo_tremor' => 0,
            'remarks' => 'Generated from Volcanic Eruption Notice (VEN)',
            'erupsi_berlangsung' => $ven->erupsi_berlangsung,
            'old_ven_uuid' => $ven->uuid,
            'group' => config('app.env') === 'local' ? 'developer' : 'real',
        ]);

        // return $request;

        $vona = new VonaService;
        $vona = $vona->storeVona($request);

        $ven->vona_created_at = now();

        return $vona;
    }
}
