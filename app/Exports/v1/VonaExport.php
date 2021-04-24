<?php

namespace App\Exports\v1;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class VonaExport implements FromArray, WithHeadings
{
    protected $vonas;

    public function __construct(array $vonas)
    {
        $this->vonas = $vonas;
    }

    public function headings(): array
    {
        return [
            'Volcano',
            'Volcano Number',
            'Issued (UTC)',
            'Current Color Code',
            'Previous Color Code',
            'Volcano Activity Summary',
            'Volcano Cloud Height (ASL)',
            'Other Volcanic Cloud Information',
        ];
    }

    public function array(): array
    {
        return $this->vonas;
    }
}
