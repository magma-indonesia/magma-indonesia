<?php

namespace App\Export;

use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\Exportable;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
Use Carbon\Carbon;

use App\MagmaVar;

class VarExport implements FromView
{
    use Exportable;

    public function __construct($request)
    {
        $this->request = $request;
    }

    /**
     * View untuk display CRS dengan filter
     *
     * @return \Illuminate\Http\Response
     */
    public function applyFilter($request)
    {

        if (count($request->all()) >0 )
        {
            switch ($request->nip) {
                case 'all':
                    $nip = '%';
                    break;
                default:
                    $nip = $request->input('nip','%');
                    break;
            }

            switch ($request->gunungapi) {
                case 'all':
                    $code = '%';
                    break;
                default:
                    $code = strtoupper($request->gunungapi);
                    break;
            }
    
            switch ($request->tipe) {
                case 'all':
                    $periode = '%';
                    break;
                default:
                    $periode = $request->tipe;
                    break;
            }
    
            $bulan = $request->input('bulan',null);        
            $start = $request->input('start',null);
            $end = $request->input('end',null);
    
            switch ($request->jenis) {
                case '0':
                    $end = Carbon::createFromFormat('Y-m-d',$start)->addDays(13)->format('Y-m-d');
                    break;
                case '1':
                    $start = Carbon::createFromFormat('Y-m-d',$bulan)->startOfMonth()->format('Y-m-d');
                    $end = Carbon::createFromFormat('Y-m-d',$bulan)->endOfMonth()->format('Y-m-d');
                    break;
                
                default:
                    $end = $end;
                    break;
            }
    
            $vars = MagmaVar::where('code_id', 'like', $code)
                        ->whereBetween('var_data_date', [$start, $end])
                        ->where('var_perwkt','like',$periode)
                        ->where('nip_pelapor','like',$nip)
                        ->orderBy('var_data_date','asc')
                        ->orderBy('created_at','desc');
    
            return $vars;
    
        }

        return array();
    }

    public function view(): View
    {      
        $vars = $this->applyFilter($this->request);
  
        $vars = $vars->get();
        
        return view('export.var',compact(
                    'vars'
                    )
                );
    }
}