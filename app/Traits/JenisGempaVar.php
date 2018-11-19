<?php

namespace App\Traits;

use Illuminate\Http\Request;

trait JenisGempaVar
{
    private function jenisgempa()
    {
        $gempa = [
            [
              'nama' => 'Tektonik Jauh',
              'kode' => 'tej',
              'jenis' => 'sp'
            ],
            [
              'nama' => 'Tektonik Lokal',
              'kode' => 'tel',
              'jenis' => 'sp'
            ],
            [
              'nama' => 'Very Long Period',
              'kode' => 'vlp',
              'jenis' => 'normal'
            ],
            [
              'nama' => 'Deep Tremor',
              'kode' => 'dpt',
              'jenis' => 'normal'
            ],
            [
              'nama' => 'Double Event',
              'kode' => 'dev',
              'jenis' => 'sp'
            ],
            [
              'nama' => 'Vulkanik Dalam',
              'kode' => 'vta',
              'jenis' => 'sp'
            ],
            [
              'nama' => 'Vulkanik Dangkal',
              'kode' => 'vtb',
              'jenis' => 'normal'
            ],
            [
              'nama' => 'Hybrid/Fase Banyak',
              'kode' => 'hyb',
              'jenis' => 'sp'
            ],
            [
              'nama' => 'Low Frequency',
              'kode' => 'lof',
              'jenis' => 'normal'
            ],
            [
              'nama' => 'Tornillo',
              'kode' => 'tor',
              'jenis' => 'normal'
            ],
            [
              'nama' => 'Harmonik',
              'kode' => 'hrm',
              'jenis' => 'normal'
            ],
            [
              'nama' => 'Tremor Non-Harmonik',
              'kode' => 'tre',
              'jenis' => 'normal'
            ],
            [
              'nama' => 'Tremor Menerus',
              'kode' => 'mtr',
              'jenis' => 'dominan'
            ],
            [
              'nama' => 'Hembusan',
              'kode' => 'hbs',
              'jenis' => 'normal'
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
              'nama' => 'Awan Panas Letusan',
              'kode' => 'apl',
              'jenis' => 'luncuran'
            ],
            [
              'nama' => 'Letusan/Erupsi',
              'kode' => 'lts',
              'jenis' => 'erupsi'
            ],
            [
              'nama' => 'Getaran Banjir',
              'kode' => 'gtb',
              'jenis' => 'normal'
            ],
            [
              'nama' => 'Terasa',
              'kode' => 'trs',
              'jenis' => 'terasa'
            ],
          ];

        return $gempa;
    }
}