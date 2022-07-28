<?php

namespace App\Traits\v1;

use App\v1\GertanCrs;

trait DeskripsiGerakanTanah
{
    /**
     * Deskripsi Laporan Gerakan Tanah
     *
     * @param GertanCrs $gertanCrs
     * @return string
     */
    public function deskripsiGerakanTanah(GertanCrs $gertanCrs): string
    {
        return 'Gerakan tanah terjadi di ' . $gertanCrs->crs_vil . ', ' . $gertanCrs->crs_rgn . ', ' . $gertanCrs->crs_cty . ', ' . $gertanCrs->crs_prv . ' pada ' . $gertanCrs->crs_dtm->formatLocalized('%A, %d %B %Y') . ' pukul ' . $gertanCrs->crs_dtm->format('H:i:s') . ' ' . $gertanCrs->crs_zon . '. Secara Geografis, lokasi kejadian gerakan tanah terletak pada posisi ' . $gertanCrs->crs_lat . ' LU dan ' . $gertanCrs->crs_lon . ' BT.';
    }
}