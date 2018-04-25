<?php

namespace App\Export;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use App\SigertanCrs;

class CrsExport implements FromQuery, WithHeadings
{
    use Exportable;

    // protected $type;

    public function __construct(int $year,array $type)
    {
        $this->year = $year;
        $this->type = $type;
    }

    public function query()
    {
        return SigertanCrs::select(
            'name',
            'phone',
            'waktu_kejadian',
            'zona',
            'type',
            'province_id',
            'city_id',
            'district_id',
            'village_id'
            )->whereIn('type',$this->type);
    }

    public function headings(): array
    {
        return [
            'Nama Pelapor',
            'No. HP',
            'Waktu Kejadian',
            'Zona Waktu',
            'Tipe Bencana',
            'Kode Provinsi',
            'Kode Kota/Kabupaten',
            'Kode Kecamatan',
            'Kode Desa/Kelurahan',
            'Provinsi'
        ];
    }

}
