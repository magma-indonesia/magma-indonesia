<?php

namespace App\Traits;

use Illuminate\Http\Request;

trait JenisGempaVar
{
    private function jenisgempa()
    {
        $gempa = [
            [
              'nama' => 'Letusan/Erupsi',
              'kode' => 'lts',
              'jenis' => 'erupsi'
            ],
            [
              'nama' => 'Awan Panas Letusan',
              'kode' => 'apl',
              'jenis' => 'luncuran'
            ],
            [
              'nama' => 'Guguran',
              'kode' => 'gug',
              'jenis' => 'luncuran'
            ],
            [
              'nama' => 'Awan Panas Guguran',
              'kode' => 'apg',
              'jenis' => 'luncuran'
            ],
            [
              'nama' => 'Hembusan',
              'kode' => 'hbs',
              'jenis' => 'normal'
            ],
            [
              'nama' => 'Tremor Non-Harmonik',
              'kode' => 'tre',
              'jenis' => 'normal'
            ],
            [
              'nama' => 'Tornillo',
              'kode' => 'tor',
              'jenis' => 'normal'
            ],
            [
              'nama' => 'Low Frequency',
              'kode' => 'lof',
              'jenis' => 'normal'
            ],
            [
              'nama' => 'Hybrid/Fase Banyak',
              'kode' => 'hyb',
              'jenis' => 'sp'
            ],
            [
              'nama' => 'Vulkanik Dangkal',
              'kode' => 'vtb',
              'jenis' => 'normal'
            ],
            [
              'nama' => 'Vulkanik Dalam',
              'kode' => 'vta',
              'jenis' => 'sp'
            ],
            [
              'nama' => 'Very Long Period',
              'kode' => 'vlp',
              'jenis' => 'normal'
            ],
            [
              'nama' => 'Tektonik Lokal',
              'kode' => 'tel',
              'jenis' => 'sp'
            ],
            [
              'nama' => 'Terasa',
              'kode' => 'trs',
              'jenis' => 'terasa'
            ],
            [
              'nama' => 'Tektonik Jauh',
              'kode' => 'tej',
              'jenis' => 'sp'
            ],
            [
              'nama' => 'Double Event',
              'kode' => 'dev',
              'jenis' => 'sp'
            ],
            [
              'nama' => 'Getaran Banjir',
              'kode' => 'gtb',
              'jenis' => 'normal'
            ],
            [
              'nama' => 'Harmonik',
              'kode' => 'hrm',
              'jenis' => 'normal'
            ],
            [
              'nama' => 'Deep Tremor',
              'kode' => 'dpt',
              'jenis' => 'normal'
            ],
            [
              'nama' => 'Tremor Menerus',
              'kode' => 'mtr',
              'jenis' => 'dominan'
            ]
          ];

        return $gempa;
    }
}