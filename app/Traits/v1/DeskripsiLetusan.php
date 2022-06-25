<?php

namespace App\Traits\v1;

use App\v1\MagmaVen;
use Carbon\Carbon;
use Illuminate\Support\Facades\URL;

trait DeskripsiLetusan
{
    /**
     * Arah abu letusan
     *
     * @param MagmaVen $ven
     * @return string
     */
    public function arahAbu(MagmaVen $ven): string
    {
        return str_replace_last(', ', ' dan ', strtolower(implode(', ', $ven->erupt_arh)));
    }

    /**
     * Intensitas abu
     *
     * @param MagmaVen $ven
     * @return string
     */
    public function intensitasAbu(MagmaVen $ven): string
    {
        return str_replace_last(', ', ' hingga ', strtolower(implode(', ', $ven->erupt_int)));
    }

    /**
     * Warna abu letusan
     *
     * @param MagmaVen $ven
     * @return string
     */
    public function warnaAbu(MagmaVen $ven): string
    {
        return str_replace_last(', ', ' hingga ', strtolower(implode(', ', $ven->erupt_wrn)));
    }

    /**
     * Kolom aku letusan
     *
     * @param MagmaVen $ven
     * @return string
     */
    public function kolomAbu(MagmaVen $ven): string
    {
        return "Kolom abu teramati berwarna {$this->warnaAbu($ven)} dengan intensitas {$this->intensitasAbu($ven)} ke arah {$this->arahAbu($ven)}.";
    }

    /**
     * Ketinggian abu letusan
     *
     * @param MagmaVen $ven
     * @return string
     */
    public function ketinggianMdpl(MagmaVen $ven): string
    {
        return (string) $ven->erupt_tka + $ven->gunungapi->ga_elev_gapi;
    }

    /**
     * Tinggi kolom letusan letusan
     *
     * @param MagmaVen $ven
     * @return string
     */
    public function ketinggian(MagmaVen $ven): string
    {
        return "Tinggi kolom letusan teramati ± {$ven->erupt_tka} m di atas puncak (± {$this->ketinggianMdpl($ven)} m di atas permukaan laut). {$this->kolomAbu($ven)}";
    }

    /**
     * Visual letusan
     *
     * @param MagmaVen $ven
     * @return string
     */
    public function visual(MagmaVen $ven): string
    {
        return $ven->erupt_vis ? $this->ketinggian($ven) : 'Tinggi kolom erupsi tidak teramati.';
    }

    /**
     * Deskripsi gempa
     *
     * @param MagmaVen $ven
     * @return string
     */
    public function gempa(MagmaVen $ven): string
    {
        if ($ven->erupsi_berlangsung) {
            return "Saat laporan ini dibuat, erupsi masih berlangsung.";
        }

        return "Erupsi terekam di seismograf dengan amplitudo maksimum {$ven->erupt_amp} mm dan durasi {$ven->erupt_drs} detik.";
    }

    /**
     * Tanggal kejadian letusan
     *
     * @param MagmaVen $ven
     * @return string
     */
    public function tanggal(MagmaVen $ven): string
    {
        $tanggal = Carbon::createFromFormat('Y-m-d', $ven->erupt_tgl)->formatLocalized('%A, %d %B %Y');
        $waktu = "{$ven->erupt_jam} {$ven->gunungapi->ga_zonearea}";
        return "pada hari $tanggal, pukul $waktu.";
    }

    /**
     * URL VEN
     *
     * @param MagmaVen $ven
     * @return string
     */
    public function url(MagmaVen $ven): string
    {
        return URL::signedRoute('v1.gunungapi.ven.show', $ven);
    }

    /**
     * Deskripsi letusan
     *
     * @param MagmaVen $ven
     * @return string
     */
    public function deskripsi(MagmaVen $ven): string
    {
        return "Terjadi erupsi G. {$ven->gunungapi->ga_nama_gapi} {$this->tanggal($ven)} {$this->visual($ven)} {$this->gempa($ven)}";
    }

    /**
     * Deskripsi letusan Telegram
     *
     * @param MagmaVen $ven
     * @return string
     */
    public function deskripsiTelegram(MagmaVen $ven): string
    {
        return "Terjadi erupsi G. {$ven->gunungapi->ga_nama_gapi} {$this->tanggal($ven)} {$this->visual($ven)} {$this->gempa($ven)} [Detail Laporan]({$this->url($ven)})";
    }

    /**
     * Tinggi kolom letusan letusan buat twitter
     *
     * @param MagmaVen $ven
     * @return string
     */
    public function ketinggianTwitter(MagmaVen $ven): string
    {
        return "Tinggi kolom letusan teramati ± {$ven->erupt_tka} m di atas puncak.";
    }

    /**
     * Deskripsi visual letusan untuk Twitter
     *
     * @param MagmaVen $ven
     * @return string
     */
    public function visualTwitter(MagmaVen $ven): string
    {
        return $ven->erupt_vis ? $this->ketinggianTwitter($ven) : 'Abu letusan tidak teramati.';
    }

    /**
     * Deskripsi letusan untuk Twitter
     *
     * @param MagmaVen $ven
     * @return string
     */
    public function deskripsiTwitter(MagmaVen $ven): string
    {
        return "Terjadi #erupsi G. {$ven->gunungapi->ga_nama_gapi} {$this->tanggal($ven)} {$this->visualTwitter($ven)} {$this->gempa($ven)} #PVMBG @id_magma";
    }
}