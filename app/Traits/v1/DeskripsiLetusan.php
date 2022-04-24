<?php

namespace App\Traits\v1;

use App\v1\MagmaVen;
use Carbon\Carbon;
use Illuminate\Support\Facades\URL;

trait DeskripsiLetusan
{
    public function arahAbu(MagmaVen $ven): string
    {
        return str_replace_last(', ', ' dan ', strtolower(implode(', ', $ven->erupt_arh)));
    }

    public function intensitasAbu(MagmaVen $ven): string
    {
        return str_replace_last(', ', ' hingga ', strtolower(implode(', ', $ven->erupt_int)));
    }

    public function warnaAbu(MagmaVen $ven): string
    {
        return str_replace_last(', ', ' hingga ', strtolower(implode(', ', $ven->erupt_wrn)));
    }

    public function kolomAbu(MagmaVen $ven)
    {
        return "Kolom abu teramati berwarna {$this->warnaAbu($ven)} dengan intensitas {$this->intensitasAbu($ven)} ke arah {$this->arahAbu($ven)}. ";
    }

    public function ketinggianMdpl(MagmaVen $ven): string
    {
        return (string) $ven->erupt_tka + $ven->gunungapi->ga_elev_gapi;
    }

    public function ketinggian(MagmaVen $ven): string
    {
        return "Tinggi kolom abu teramati ± {$ven->erupt_tka} m di atas puncak (± {$this->ketinggianMdpl($ven)} m di atas permukaan laut). {$this->kolomAbu($ven)}";
    }

    public function visual(MagmaVen $ven): string
    {
        return $ven->erupt_vis ? $this->ketinggian($ven) : 'Visual letusan tidak teramati.';
    }

    public function gempa(MagmaVen $ven): string
    {
        if ($ven->erupsi_berlangsung) {
            return "Saat laporan ini dibuat, erupsi masih berlangsung.";
        }

        return "Erupsi terekam di seismograf dengan amplitudo maksimum {$ven->erupt_amp} mm dan durasi {$ven->erupt_drs} detik.";
    }

    public function tanggal(MagmaVen $ven): string
    {
        $tanggal = Carbon::createFromFormat('Y-m-d', $ven->erupt_tgl)->formatLocalized('%A, %d %B %Y');
        $waktu = "{$ven->erupt_jam} {$ven->gunungapi->ga_zonearea}";
        return "pada hari $tanggal, pukul $waktu.";
    }

    public function url(MagmaVen $ven): string
    {
        return URL::signedRoute('v1.gunungapi.ven.show', $ven);
    }

    public function deskripsi(MagmaVen $ven): string
    {
        return "Terjadi erupsi G. {$ven->gunungapi->ga_nama_gapi} {$this->tanggal($ven)} {$this->visual($ven)} {$this->gempa($ven)}";
    }

    public function deskripsiTelegram(MagmaVen $ven): string
    {
        return "Terjadi erupsi G. {$ven->gunungapi->ga_nama_gapi} {$this->tanggal($ven)} {$this->visual($ven)} {$this->gempa($ven)} [Detail Laporan]({$this->url($ven)})";
    }

    public function ketinggianTwitter(MagmaVen $ven): string
    {
        return "Tinggi kolom abu teramati ± {$ven->erupt_tka} m di atas puncak.";
    }

    public function visualTwitter(MagmaVen $ven): string
    {
        return $ven->erupt_vis ? $this->ketinggianTwitter($ven) : 'Visual letusan tidak teramati.';
    }

    public function deskripsiTwitter(MagmaVen $ven): string
    {
        return "Terjadi #erupsi G. {$ven->gunungapi->ga_nama_gapi} {$this->tanggal($ven)} {$this->visualTwitter($ven)} {$this->gempa($ven)} #PVMBG @id_magma";
    }
}