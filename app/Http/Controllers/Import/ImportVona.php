<?php

namespace App\Http\Controllers\Import;

use Illuminate\Http\Request;
use App\Vona;
use App\v1\Vona as OldVona;
use App\Traits\ImportHelper;

class ImportVona extends Import
{
    use ImportHelper;

    public function __construct()
    {
        ini_set('max_execution_time', 1200);
    }

    public function import()
    {
        $this->old = OldVona::all();

        $this->old->each(function ($item,$key) {
            $this->setItem($item)
                ->createVona();
        });

        $data = $this->data
                ? [ 'success' => 1, 'text' =>'VONA', 'message' => 'VONA berhasil diperbarui', 'count' => Vona::count() ] 
                : [ 'success' => 0, 'text' => 'VONA', 'message' => 'VONA gagal diperbarui', 'count' => 0 ];

        $this->sendNotif($data);

        return response()->json($this->status);
    }

    protected function createVona()
    {
        $no = $this->item->no;
        $noticenumber = $this->item->notice_number;
        $pelapor = empty($this->item->nip) ? '198803152015031005' : $this->item->nip;
        $pelapor = $pelapor == '196807071992051001' || $pelapor == '196807071992031001' ? '196807071992031018' : $pelapor;

        $type = $this->item->type == 'REAL' ? 'REAL' : 'EXERCISE';

        try {
            $create = Vona::firstOrCreate(
                [
                    'noticenumber' => $noticenumber,
                ],
                [
                    'issued' => $this->item->issued,
                    'type' => $type,
                    'code_id' => $this->item->ga_code,
                    'cu_code' => $this->item->cu_avcode,
                    'prev_code' => $this->item->pre_avcode,                    
                    'location' => $this->item->volcano_location,
                    'vas' => $this->item->volcanic_act_summ,
                    'vch_summit' => $this->item->vc_height > 0 ? ($this->item->vc_height - $this->item->summit_elevation)*0.3048 : 0,
                    'vch_asl' => $this->item->vc_height*0.3048,
                    'vch_other' => $this->item->other_vc_info,
                    'remarks' => strlen($this->item->remarks)<6 ? null : $this->item->remarks,
                    'sent' => $this->item->sent,
                    'nip_pelapor' => $pelapor
                ]
            );

            if ($create) {
                $this->data = $this->tempTable('vona',$no);
            }
        }

        catch (Exception $e) {
            $this->sendError($e);
        }

        return $this;
    }
}