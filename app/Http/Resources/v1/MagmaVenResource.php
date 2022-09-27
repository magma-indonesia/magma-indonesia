<?php

namespace App\Http\Resources\v1;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\URL;

class MagmaVenResource extends JsonResource
{
    protected function visualTeramati()
    {
        return "Terjadi erupsi G. {$this->gunungapi->ga_nama_gapi} pada hari {$this->localizedDate()}, pukul {$this->localizedTime()} dengan tinggi kolom abu teramati &plusmn; {$this->erupt_tka} m di atas puncak (&plusmn; {$this->tinggiKolomAbuMdpl()} m di atas permukaan laut). Kolom abu teramati berwarna {$this->deskripsiWarnaAbu()} dengan intensitas {$this->deskripsiIntensitasAbu()} ke arah {$this->deskripsiArahAbu()}.";
    }

    protected function visualTidakTeramati()
    {
        return "Terjadi erupsi G. {$this->gunungapi->ga_nama_gapi} pada hari {$this->localizedDate()}, pukul {$this->localizedTime()}. Visual letusan tidak teramati.";
    }

    protected function localizedDate()
    {
        return Carbon::createFromFormat('Y-m-d', $this->erupt_tgl)->formatLocalized('%A, %d %B %Y');
    }

    protected function localizedTime()
    {
        return "{$this->erupt_jam} {$this->gunungapi->ga_zonearea}";
    }

    protected function tinggiKolomAbuMdpl()
    {
        return $this->erupt_tka + $this->gunungapi->ga_elev_gapi;
    }

    protected function isoDateTime()
    {
        switch ($this->gunungapi->ga_zonearea) {
            case 'WIB':
                $tz = 'Asia/Jakarta';
                break;
            case 'WITA':
                $tz = 'Asia/Makassar';
                break;
            default:
                $tz = 'Asia/Jayapura';
                break;
        }

        $isoDateTime = Carbon::createFromFormat('Y-m-d H:i:s', "{$this->erupt_tgl} {$this->erupt_jam}:00", $tz)
            ->toIso8601String();

        return $isoDateTime;
    }

    protected function warnaAbu()
    {
        return $this->erupt_wrn;
    }

    protected function deskripsiWarnaAbu()
    {
        return str_replace_last(', ', ' hingga ', strtolower(implode(', ', $this->erupt_wrn)));
    }

    protected function intensitasAbu()
    {
        return $this->erupt_int;
    }

    protected function deskripsiIntensitasAbu()
    {
        return str_replace_last(', ', ' hingga ', strtolower(implode(', ', $this->erupt_int)));
    }

    protected function arahAbu()
    {
        return $this->erupt_arh;
    }

    protected function deskripsiArahAbu()
    {
        return str_replace_last(', ', ' dan ', strtolower(implode(', ', $this->erupt_arh)));
    }

    protected function tingkatAktivitas()
    {
        switch ($this->erupt_sta) {
            case 1:
                return 'Level I (Normal)';
            case 2:
                return 'Level II (Waspada)';
            case 3:
                return 'Level III (Siaga)';
            default:
                return 'Level IV (Awas)';
        }
    }

    protected function signedUrl()
    {
        return URL::signedRoute('v1.gunungapi.ven.show', $this);
    }

    protected function shareVisualTeramati()
    {
        return "Terjadi erupsi G. {$this->gunungapi->ga_nama_gapi} pada hari {$this->localizedDate()}, pukul {$this->localizedTime()} tinggi kolom abu &plusmn; {$this->erupt_tka} m di atas puncak.{$this->shareInstrumental()}";
    }

    protected function shareVisualTidakTeramati()
    {
        return "Terjadi erupsi G. {$this->gunungapi->ga_nama_gapi} pada hari {$this->localizedDate()}, pukul {$this->localizedTime()}.{$this->shareInstrumental()}";
    }

    protected function shareInstrumental()
    {
        if ($this->erupt_amp) {
            return " Erupsi terekam di seismograf dengan amplitudo maksimum {$this->erupt_amp } mm dan durasi {$this->erupt_drs} detik.";
        }

        return;
    }

    protected function raw()
    {
        return [
            'id' => $this->erupt_id,
            'code_ga' => $this->ga_code,
            'nama_gunung_api' => $this->gunungapi->ga_nama_gapi,
            'latitude' => $this->gunungapi->ga_lat_gapi,
            'longitude' => $this->gunungapi->ga_lon_gapi,
            'elevation' => $this->gunungapi->ga_elev_gapi,
            'local_date' => $this->erupt_tgl,
            'local_time' => $this->erupt_jam,
            'local_datetime' => "{$this->erupt_tgl} {$this->erupt_jam}:00",
            'time_zone' => $this->gunungapi->ga_zonearea,
            'iso_datetime' => $this->isoDateTime(),
            'foto' => $this->erupt_pht ?: null,
            'tingkat_aktivitas' => $this->tingkatAktivitas(),
            'tinggi_kolom_abu' => $this->erupt_tka ?: null,
            'tinggi_kolom_abu_mdpl' => $this->erupt_tka ?
                $this->tinggiKolomAbuMdpl() :
                null,
            'warna_abu' => $this->erupt_vis ? $this->warnaAbu() : null,
            'intensitas_abu' => $this->erupt_vis ? $this->intensitasAbu() : null,
            'arah_abu' => $this->erupt_vis ? $this->arahAbu() : null,
            'rekomendasi' => nl2br($this->erupt_rek),
            'pelapor' => $this->user->vg_nama,
            'share' => [
                'url' =>  $this->signedUrl(),
                'description' => $this->erupt_vis ? $this->shareVisualTeramati() : $this->shareVisualTidakTeramati(),
                'photo' => $this->erupt_pht ?: null,
            ],
        ];
    }

    protected function description()
    {
        return [
            'id' => $this->erupt_id,
            'code_ga' => $this->ga_code,
            'nama_gunung_api' => $this->gunungapi->ga_nama_gapi,
            'latitude' => $this->gunungapi->ga_lat_gapi,
            'longitude' => $this->gunungapi->ga_lon_gapi,
            'elevation' => $this->gunungapi->ga_elev_gapi,
            'local_date' => $this->erupt_tgl,
            'local_time' => $this->erupt_jam,
            'local_datetime' => "{$this->erupt_tgl} {$this->erupt_jam}:00",
            'time_zone' => $this->gunungapi->ga_zonearea,
            'iso_datetime' => $this->isoDateTime(),
            'foto' => $this->erupt_pht ?: null,
            'tingkat_aktivitas' => $this->tingkatAktivitas(),
            'deskripsi' => [
                'visual' => $this->erupt_vis ? $this->visualTeramati() : $this->visualTidakTeramati(),
                'instrumental' => "Erupsi ini terekam di seismograf dengan amplitudo maksimum {$this->erupt_amp} mm dan durasi {$this->erupt_drs} detik."
            ],
            'rekomendasi' => nl2br($this->erupt_rek),
            'pelapor' => $this->user->vg_nama,
            'share' => [
                'url' =>  $this->signedUrl(),
                'description' => $this->erupt_vis ? $this->shareVisualTeramati() : $this->shareVisualTidakTeramati(),
                'photo' => $this->erupt_pht ?: null,
            ],
        ];
    }

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        if ($request->has('raw'))
        {
            return $request->raw ? $this->raw() : $this->description();
        }

        return $this->description();
    }
}
