<?php

namespace App\Services;

use App\Traits\VonaTrait;
use App\Vona;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class VonaService
{
    use VonaTrait;

    /**
     * Store VONA
     *
     * @param Request $request
     * @return Vona
     */
    public function storeVona(Request $request): Vona
    {
        $vona = Vona::create([
            'issued' => $this->issued($request),
            'type' => Str::upper($request->type),
            'code_id' => $request->code,
            'is_visible' => $request->visibility,
            'is_continuing' => $request->erupsi_berlangsung,
            'current_code' => $this->currentCode($request),
            'previous_code' => $this->previousCode($request),
            'ash_height' => $request->visibility ? $request->height : 0,
            'ash_color' => $request->visibility ? $request->warna_asap : null,
            'ash_intensity' => $request->visibility ? $request->intensitas : null,
            'ash_directions' => $request->visibility ? $request->arah_abu : null,
            'amplitude' => ($request->terjadi_gempa_letusan || $request->code == 'green') ?
                ($request->amplitudo ?? 0) : 0,
            'amplitude_tremor' => ($request->terjadi_tremor || $request->code == 'green') ?
                ($request->amplitudo_tremor ?? 0) : 0,
            'duration' => $this->duration($request),
            'remarks' => $request->remarks,
            'nip_pelapor' => auth()->user()->nip,
            'old_ven_uuid' => $request->old_ven_uuid,
        ]);

        $oldVona = $this->storeToOldVona($request, $vona);

        $vona->update([
            'old_id' => $oldVona->no,
            'noticenumber' => $oldVona->notice_number,
        ]);

        return $vona;
    }

    public function sendVona(Vona $vona, Request $request)
    {

    }
}