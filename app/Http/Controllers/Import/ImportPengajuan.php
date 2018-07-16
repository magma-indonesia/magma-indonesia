<?php

namespace App\Http\Controllers\Import;

use Illuminate\Http\Request;
use App\Pengajuan;
use App\v1\Pengajuan as OldPengajuan;

class ImportPengajuan extends Import
{
    public function __construct()
    {
        ini_set('max_execution_time', 1200);
    }

    public function import()
    {
        $this->old = OldPengajuan::all();

        $this->old->each(function ($item,$key) {
            $this->setItem($item)->createPengajuan();
        });

        $data = $this->data
                    ? [ 'success' => 1, 'text' => 'Data Pengajuan', 'message' => 'Data Pengajuan berhasil diperbarui', 'count' => Pengajuan::count() ] 
                    : [ 'success' => 0, 'text' => 'Data Pengajuan', 'message' => 'Data Pengajuan gagal diperbarui', 'count' => 0 ];

        $this->sendNotif($data);

        return response()->json($this->status);
    }

    protected function createPengajuan()
    {
        try {
            $create = Pengajuan::firstOrCreate(
                [
                    'created_at' => $this->item->waktu_tanya,
                ],
                [
                    'nip_id' => $this->item->vg_nip,
                    'topik' => $this->item->topik,
                    'pertanyaan' => $this->item->tanya,
                    'foto_pertanyaan' => $this->item->bukti1,
                    'follow_up' => $this->item->followup,
                    'nip_pemroses' => $this->item->oleh_nip,
                    'jawaban' => $this->item->jawab,
                    'foto_jawaban' => $this->item->jawab,
                    'answered_at' => $this->item->waktu_jawab,
                ]
            );

            $this->data = $create ? true : false;

            return $this;
        }

        catch (Exception $e) {
            $this->sendError($e);
        }
    }
}