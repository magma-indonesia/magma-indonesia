<?php

namespace App\Http\Controllers\Import;

use Illuminate\Http\Request;
use App\VarRekomendasi;
use App\v1\Gadd as OldDd;
use App\Traits\ImportHelper;

class ImportVarRekomendasi extends Import 
{

    use ImportHelper;

    public function __construct()
    {
        $this->old = OldDd::select('ga_code')->with('rekomendasi')->get();
    }

    public function __invoke()
    {
        $this->old->each(function ($item,$key) {
            $item->rekomendasi->each(function ($item,$key) {
                $this->setItem($item)->createRekomendasi();
            });
        });

        $data = $this->data
            ? [ 'success' => 1, 'text' => 'Var Rekomendasi', 'message' => 'Var Rekomendasi berhasil diperbarui', 'count' => VarRekomendasi::count() ] 
            : [ 'success' => 0, 'text' => 'Var Rekomendasi', 'message' => 'DVar Rekomendasi gagal diperbarui', 'count' => 0 ];

        $this->sendNotif($data);

        return response()->json($this->status);
    }

    protected function createRekomendasi()
    {
        try {
            $create = VarRekomendasi::firstOrCreate(
                [
                    'code_id' => $this->item->ga_code,
                    'status' => $this->item->cu_status
                ],
                [
                    'rekomendasi' => $this->item->var_rekom
                ]
            );

            if ($create) {
                $this->data = true;
            }

            return $this;
        }

        catch (Exception $e) {
            $this->sendError($e);
        }
    }
}