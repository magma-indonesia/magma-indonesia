<?php

namespace App\Http\Controllers\Import;

use App\MagmaVen;
use App\v1\MagmaVen as OldVen;
use App\Traits\ImportHelper;
use Exception;

class ImportMagmaVen extends Import
{
    use ImportHelper;

    public function __construct()
    {
        ini_set('max_execution_time', 1200);
    }

    public function import()
    {
        $this->old = OldVen::whereBetween('erupt_id',[$this->startNo('ven'),$this->endNo('ven')])->get();
        $this->old->each(function ($item, $key) {
            $this->setItem($item)
                ->createVen();
        });

        $data = $this->data
            ? [ 'success' => 1, 'text' => 'Data MAGMA-VEN', 'message' => 'Magma Ven berhasil diperbarui', 'count' => MagmaVen::count() ] 
            : [ 'success' => 0, 'Data MAGMA-VEN' => 'Data MAGMA-VEN', 'message' => 'Magma Ven gagal diperbarui', 'count' => 0 ];

        $this->sendNotif($data);

        return response()->json($this->status);
    }

    protected function createVen()
    {
        $no = $this->item->erupt_id;

        try {
            $create = MagmaVen::firstOrCreate(
                [
                    'code_id' => $this->item->ga_code,
                    'date' => $this->item->erupt_tgl.' '.$this->item->erupt_jam.':00',
                ],
                [
                    'height' => $this->item->erupt_tka,
                    'wasap' => $this->item->erupt_wrn,
                    'intensitas' => $this->item->erupt_int,
                    'visibility' => $this->item->erupt_vis,
                    'arah_asap' => $this->item->erupt_arh,
                    'amplitudo' => $this->item->erupt_amp,
                    'durasi' => $this->item->erupt_drs,        
                    'photo' => $this->item->erupt_pht,
                    'status' => $this->item->erupt_sta,
                    'rekomendasi' => $this->item->erupt_rek,
                    'lainnya' => $this->item->erupt_ket,
                    'nip_pelapor' => $this->item->erupt_usr,
                    'created_at' => $this->item->tsp
                ]
            );

            if ($create) {
                $this->data = $this->tempTable('ven',$no);
            }

            return $this;
        }

        catch (Exception $e) {
            $data = [
                'success' => 0,
                'message' => $e
            ];
            
            return response()->json($data);
        }
        
    }
}