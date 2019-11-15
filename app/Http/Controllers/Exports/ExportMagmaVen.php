<?php

namespace App\Http\Controllers\Exports;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\MagmaVen as NewVen;
use App\v1\MagmaVen as OldVen;
use App\Traits\ExportHelper;

class ExportMagmaVen extends Export
{
    use ExportHelper;

    protected $status;

    public function __construct()
    {
        ini_set('max_execution_time',1200);
    }

    public function export()
    {
        $this->new = NewVen::with(
                        'gunungapi','user'
                    )
                    ->orderBy('date')
                    ->whereBetween('date',['2018-05-24',now()->format('Y-m-d')]);

        $this->new->chunk(100, function($items) {
            foreach ($items as $key => $item) {
                $this->setItem($item)
                    ->setStatus()
                    ->updateOldVen();
            }
        });

        $data = $this->data
                    ? [ 'success' => 1, 'text' => 'Data MAGMA-VEN v1', 'message' => 'Data VEN berhasil diperbarui', 'count' => OldVen::count() ] 
                    : [ 'success' => 0, 'text' => 'Data MAGMA-VEN v1', 'message' => 'Data VEN gagal diperbarui', 'count' => 0 ];

        return response()->json($data);

    }

    protected function updateOldVen()
    {
        try {
            $update = OldVen::firstOrCreate(
                [
                    'ga_code' => $this->item->code_id,
                    'erupt_tgl' => $this->item->date->format('Y-m-d'),
                    'erupt_jam' => $this->item->date->format('H:i')
                ],
                [
                    'erupt_vis' => $this->item->visibility,
                    'erupt_tka' => $this->item->height,
                    'erupt_wrn' => implode(', ',$this->item->wasap ?? ['-']),
                    'erupt_int' => implode(', ',$this->item->intensitas ?? ['-']),
                    'erupt_arh' => implode(', ',$this->item->arah_asap ?? ['-']),
                    'erupt_amp' => $this->item->amplitudo,
                    'erupt_drs' => $this->item->durasi,
                    'erupt_pht' => $this->item->photo ?? '-',
                    'erupt_sta' => $this->status,
                    'erupt_rek' => $this->item->rekomendasi,
                    'erupt_ket' => $this->item->lainnya ?? '-',
                    'erupt_usr' => $this->item->nip_pelapor,
                    'erupt_tsp' => $this->item->updated_at
                ]
            );

            $this->data = $update ? true : false;
            return $this; 
        }

        catch (Exception $e) {
            $this->sendError($e);
        };
    }
}
