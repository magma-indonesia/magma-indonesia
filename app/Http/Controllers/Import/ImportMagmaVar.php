<?php

namespace App\Http\Controllers\Import;

use Illuminate\Http\Request;
use App\MagmaVar;
use App\VarPj;
use App\VarVerifikator;
use App\VarKeteranganLain;
use App\v1\MagmaVar as OldVar;
use App\Traits\ImportHelper;

class ImportMagmaVar extends Import
{
    use ImportHelper;

    protected $obscode, $noticenumber, $pj, $verifikator, $lainnya;

    public function __construct()
    {
        ini_set('max_execution_time', 1200);
        $this->old = OldVar::select(
            'no','ga_code','var_noticenumber',
            'cu_status','var_issued','var_data_date',
            'periode','var_perwkt','var_source','var_nip_pelapor',
            'var_nip_pemeriksa_pj','var_nip_pemeriksa','var_log','var_ketlain')
        ->whereBetween('no',[$this->startNo('vars'),$this->endNo('var')])
        ->orderBy('no');
    }

    public function __invoke()
    {
        $this->old->chunk(5000, function ($items) {
            foreach ($items as $key => $item) {
                if (!empty($item->var_data_date)) {
                    $this->setItem($item)
                        ->createVar()
                        ->createPj()
                        ->createVerifikator()
                        ->createLainnya();
                }
            }
        });

        $data = $this->data
                    ? [ 'success' => 1, 'text' => 'Data MAGMA-VAR v1', 'message' => 'Data Vars berhasil diperbarui', 'count' => MagmaVar::count() ] 
                    : [ 'success' => 0, 'text' => 'Data MAGMA-VAR v1', 'message' => 'Data Vars gagal diperbarui', 'count' => 0 ];

        $this->sendNotif($data);

        return response()->json($this->status);
    }

    protected function createVar()
    {
        $no = $this->item->no;
        $gacode = $this->item->ga_code;
        $var_nip_pelapor = $this->item->var_nip_pelapor;
        $this->pj = $this->item->var_nip_pemeriksa_pj;
        $this->verifikator = $this->item->var_nip_pemeriksa;
        $var_source = $this->item->var_source;

        $var_nip_pelapor = empty($var_nip_pelapor) 
            ? '198803152015031005' : $var_nip_pelapor;
        $var_nip_pelapor = $var_nip_pelapor == '3273182505850001' 
            ? '3273182505850007' : $var_nip_pelapor;
        $var_nip_pelapor = $var_nip_pelapor == '196807071992051001' || $var_nip_pelapor == '196807071992031001' 
            ? '196807071992031018' : $var_nip_pelapor;

        $this->obscode = $this->obscode($gacode,$var_source);
        $this->noticenumber = $this->obscode.$this->item->var_noticenumber;

        $this->lainnya = $this->item->var_ketlain;

        try {
            $create = MagmaVar::firstOrCreate(
                [
                    'noticenumber'          => $this->noticenumber
                ],
                [
                    'code_id'               => $this->item->ga_code,
                    'var_data_date'         => $this->item->var_data_date,
                    'periode'               => $this->item->periode,
                    'var_perwkt'            => $this->item->var_perwkt,
                    'obscode_id'            => $this->obscode,
                    'status'                => $this->item->cu_status,
                    'nip_pelapor'           => $var_nip_pelapor,
                    'created_at'            => $this->item->var_issued,
                    'updated_at'            => $this->item->var_log
                ]
            );
     
            if ($create) {
                $this->data = $this->tempTable('vars',$no);
            }

            return $this;
        }

        catch (Exception $e) {
            $this->sendError($e);
        }

    }

    protected function createPj()
    {
        if (!empty($this->pj)) {

            $create = VarPj::firstOrCreate(
                [
                    'noticenumber_id' => $this->noticenumber
                ],
                [
                    'nip_id' => $this->pj,
                ]
            );

        }

        return $this;
    }

    protected function createVerifikator()
    {
        if (!empty($this->verifikator)) {

            $data = VarVerifikator::firstOrCreate(
                [
                    'noticenumber_id' => $this->noticenumber
                ],
                [
                    'nip_id' => $this->verifikator,
                ]
            );

        }

        return $this;
    }

    protected function createLainnya()
    {
        if (!empty($this->lainnya)) {
            try {
                $create = VarKeteranganLain::firstOrCreate(
                    [
                        'noticenumber_id' => $this->noticenumber
                    ],
                    [
                        'deskripsi' => ucfirst($this->lainnya)
                    ]
                );

                return $this;
            }

            catch (Exception $e) {
                $this->sendError($e);
            }            
        }
    }

}