<?php

namespace App\Http\Controllers\Export;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\MagmaVar as NewVar;
use App\v1\MagmaVar as OldVar;
use App\Traits\ExportHelper;

class ExportMagmaVarBackup extends Export
{
    use ExportHelper;
    
    protected $noticenumber;
    protected $pj;
    protected $verifikator;
    protected $lainnya;
    protected $status;
    protected $tasap_min;
    protected $tasap_max;
    protected $wasap;
    protected $intasap;
    protected $tekasap;

    public function __construct()
    {
        ini_set('max_execution_time',1200);
    }

    public function export()
    {

        $this->new = NewVar::with('visual')
                        ->orderBy('created_at')
                        ->whereBetween('var_data_date',['2018-06-01','2018-07-31']);

        $this->new->chunk(5000, function($items) {
            foreach ($items as $key => $item) {
                $this->setItem($item)
                    ->setNoticeNumber()
                    ->updateOldVar();
            }
        });

        return OldVar::select('ga_code','var_data_date','var_viskawah')->where('ga_code','KAR')->whereBetween('var_data_date',['2018-07-01','2018-08-31'])->get();

        $data = $this->data
                    ? [ 'success' => 1, 'text' => 'Data MAGMA-VAR v1', 'message' => 'Data Vars berhasil diperbarui', 'count' => OldVar::count() ] 
                    : [ 'success' => 0, 'text' => 'Data MAGMA-VAR v1', 'message' => 'Data Vars gagal diperbarui', 'count' => 0 ];

        return response()->json($data);
        
    }

    protected function updateOldVar()
    {

        try {

            $update = OldVar::updateOrCreate(
                [
                    'ga_code' => $this->item->code_id,
                    'var_noticenumber' => $this->noticenumber
                ],
                [
                    'var_viskawah' => $this->item->visual->visual_kawah,
                ]
            );

            $this->setStartDate($this->item->var_data_date->format('Y-m-d'));

            $this->data = $update ? true : false;
            return $this;
        }

        catch (Exception $e) {
            $this->sendError($e);
        }

    }

}
