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
              'jenis' => 'sp',
              'parameter' => ['jumlah','amin','amax','spmin','spmax','dmin','dmax']
            ],
            [
              'nama' => 'Tektonik Lokal',
              'kode' => 'tel',
              'jenis' => 'sp',
              'parameter' => ['jumlah','amin','amax','spmin','spmax','dmin','dmax']
            ],
            [
              'nama' => 'Very Long Period',
              'kode' => 'vlp',
              'jenis' => 'normal',
              'parameter' => ['jumlah','amin','amax','dmin','dmax']
            ],
            [
              'nama' => 'Deep Tremor',
              'kode' => 'dpt',
              'jenis' => 'normal',
              'parameter' => ['jumlah','amin','amax','dmin','dmax']
            ],
            [
              'nama' => 'Double Event',
              'kode' => 'dev',
              'jenis' => 'sp',
              'parameter' => ['jumlah','amin','amax','spmin','spmax','dmin','dmax']
            ],
            [
              'nama' => 'Vulkanik Dalam',
              'kode' => 'vta',
              'jenis' => 'sp',
              'parameter' => ['jumlah','amin','amax','spmin','spmax','dmin','dmax']
            ],
            [
              'nama' => 'Vulkanik Dangkal',
              'kode' => 'vtb',
              'jenis' => 'normal',
              'parameter' => ['jumlah','amin','amax','dmin','dmax']
            ],
            [
              'nama' => 'Hybrid/Fase Banyak',
              'kode' => 'hyb',
              'jenis' => 'sp',
              'parameter' => ['jumlah','amin','amax','spmin','spmax','dmin','dmax']
            ],
            [
              'nama' => 'Low Frequency',
              'kode' => 'lof',
              'jenis' => 'normal',
              'parameter' => ['jumlah','amin','amax','dmin','dmax']
            ],
            [
              'nama' => 'Tornillo',
              'kode' => 'tor',
              'jenis' => 'normal',
              'parameter' => ['jumlah','amin','amax','dmin','dmax']
            ],
            [
              'nama' => 'Harmonik',
              'kode' => 'hrm',
              'jenis' => 'normal',
              'parameter' => ['jumlah','amin','amax','dmin','dmax']
            ],
            [
              'nama' => 'Tremor Non-Harmonik',
              'kode' => 'tre',
              'jenis' => 'normal',
              'parameter' => ['jumlah','amin','amax','dmin','dmax']
            ],
            [
              'nama' => 'Tremor Menerus',
              'kode' => 'mtr',
              'jenis' => 'dominan',
              'parameter' => ['jumlah','amin','amax','adom']
            ],
            [
              'nama' => 'Hembusan',
              'kode' => 'hbs',
              'jenis' => 'normal',
              'parameter' => ['jumlah','amin','amax','dmin','dmax']
            ],
            [
              'nama' => 'Guguran',
              'kode' => 'gug',
              'jenis' => 'luncuran',
              'parameter' => ['jumlah','amin','amax','dmin','dmax','rmin','rmax','arah']
            ],
            [
              'nama' => 'Awan Panas Guguran',
              'kode' => 'apg',
              'jenis' => 'luncuran',
              'parameter' => ['jumlah','amin','amax','dmin','dmax','rmin','rmax','arah']
            ],
            [
              'nama' => 'Awan Panas Letusan',
              'kode' => 'apl',
              'jenis' => 'luncuran',
              'parameter' => ['jumlah','amin','amax','dmin','dmax','rmin','rmax','arah']
            ],
            [
              'nama' => 'Letusan/Erupsi',
              'kode' => 'lts',
              'jenis' => 'erupsi',
              'parameter' => ['jumlah','amin','amax','dmin','dmax']
            ],
            [
              'nama' => 'Getaran Banjir',
              'kode' => 'gtb',
              'jenis' => 'normal',
              'parameter' => ['jumlah','amin','amax','dmin','dmax']
            ],
            [
              'nama' => 'Terasa',
              'kode' => 'trs',
              'jenis' => 'terasa',
              'parameter' => ['jumlah','amin','amax','spmin','spmax','dmin','dmax','skala']
            ],
          ];

        return $gempa;
    }
}