<?php

namespace App\Exports\v1;

use App\v1\MagmaRoq;
use Maatwebsite\Excel\Excel;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\Exportable;

class RoqsExport implements FromView, Responsable
{
    use Exportable;

    private $fileName = 'magma.xlsx';

    private $writerType = Excel::XLSX;

    private $filtereds;

    public function __construct($request, $filtereds)
    {
        $this->start = $request->start;
        $this->end = $request->end;
        $this->filtereds = $filtereds;
        $this->fileName = 'magma_gempabumi_'.$request->start.'_'.$request->end.'.xlsx';
    }

    /**
    * @return \Illuminate\Support\Query
    */
    public function view(): View
    {
        return view('v1.exports.roqs', [
            'roqs' => $this->filtereds
        ]);
    }
}
