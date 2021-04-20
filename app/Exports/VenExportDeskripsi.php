<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class VenExportDeskripsi implements FromArray, WithHeadings
{
    protected $vens;

    public function __construct(array $vens)
    {
        $this->vens = $vens;
    }

    public function headings(): array
    {
        return [
            'Gunung Api',
            'Waktu Kejadian',
            'Zona Waktu',
            'Visual Letusan Teramati',
            'Tinggi Letusan (m, di atas puncak)',
            'Deskripsi Letusan',
            'Deskripsi Seismik',
            'Rekomendasi'
        ];
    }

    public function array(): array
    {
        return $this->vens;
    }
}
