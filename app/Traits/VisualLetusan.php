<?php

namespace App\Traits;

use Carbon\Carbon;

trait VisualLetusan
{
    /**
     * teramati
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\MagmaVen $ven
     * @return void
     */
    protected function teramati($request,$ven)
    {
        $ven->code_id = $request->code;
        $ven->visibility = $request->visibility;
        $ven->date = Carbon::createFromFormat('Y-m-d H:i',$request->date)->format('Y-m-d');
        $ven->time = Carbon::createFromFormat('Y-m-d H:i',$request->date)->format('H:i');
        $ven->height = $request->height;
        $ven->wasap = $request->wasap;
        $ven->intensitas = $request->intensitas;
        $ven->arah_asap = $request->arah;
        $ven->amplitudo = $request->amplitudo;
        $ven->durasi = $request->durasi;
        $ven->photo = null;
        $ven->status = $request->status;
        $ven->rekomendasi = $request->rekomendasi;
        $ven->lainnya = $request->lainnya;
        $ven->nip_pelapor = auth()->user()->nip;

        return $ven->save();
    }

    /**
     * tidakTeramati
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\MagmaVen $ven
     * @return void
     */
    protected function tidakTeramati($request,$ven)
    {
        $ven->code_id = $request->code;
        $ven->visibility = $request->visibility;
        $ven->date = Carbon::createFromFormat('Y-m-d H:i',$request->date)->format('Y-m-d');
        $ven->time = Carbon::createFromFormat('Y-m-d H:i',$request->date)->format('H:i');
        $ven->status = $request->status;
        $ven->amplitudo = $request->amplitudo;
        $ven->durasi = $request->durasi;
        $ven->rekomendasi = $request->rekomendasi;
        $ven->lainnya = $request->lainnya;
        $ven->nip_pelapor = auth()->user()->nip;

        return $ven->save();
    }

    /**
     * visualTeramati
     *
     * @param \App\MagmaVen $ven
     * @return void
     */
    protected function visualTeramati($ven)
    {
        $asl = $ven->height+$ven->gunungapi->elevation;

        $wasap = count($ven->wasap) > 1 
            ? str_replace_last(', ',' hingga ', strtolower(implode(', ',$ven->wasap))) 
            : strtolower($ven->wasap[0]);

        $intensitas = count($ven->intensitas) > 1 
            ? strtolower($ven->intensitas[0]).' hingga '.strtolower(last($ven->intensitas)) 
            : strtolower($ven->intensitas[0]);

        $arah = count($ven->arah_asap) > 1 
            ? str_replace_last(', ',' dan ', strtolower(implode(', ',$ven->arah_asap))) 
            : strtolower($ven->arah_asap[0]);
        
        $data = 'Telah terjadi erupsi G. '. $ven->gunungapi->name .', '. $ven->gunungapi->province .' pada tanggal '. $ven->date .', pukul '. $ven->time.' '.$ven->gunungapi->zonearea.' dengan tinggi kolom abu teramati &plusmn; '. $ven->height .' m di atas puncak (&plusmn; '. $asl .' m di atas permukaan laut). Kolom abu teramati berwarna '. $wasap .' dengan intensitas '. $intensitas .' ke arah '. $arah .'. Erupsi ini terekam di seismograf dengan amplitudo maksimum '.$ven->amplitudo.' mm dan durasi '.$ven->durasi.' detik.';

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
        $data = 'Telah terjadi erupsi G. '. $ven->gunungapi->name .', '. $ven->gunungapi->province .' pada tanggal '. $ven->date .', pukul '. $ven->time.' '.$ven->gunungapi->zonearea.'. Visual letusan tidak teramati. Erupsi ini terekam di seismograf dengan amplitudo maksimum '.$ven->amplitudo.' mm dan durasi '.$ven->durasi.' detik.';

        return $data;
    }

    /**
     * visualLetusan
     *
     * @param \App\MagmaVen $ven
     * @return void
     */
    protected function visualLetusan($ven)
    {
        $data = $ven->visibility == 1 
            ? $this->visualTeramati($ven)
            : $this->visualTidakTeramati($ven);
        
        return $data;
    }
}