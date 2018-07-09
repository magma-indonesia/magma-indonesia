<?php

namespace App\Http\Controllers\Import;

use Illuminate\Http\Request;
use App\VarRekomendasi;
use App\MagmaVar;
use App\v1\MagmaVar as OldVar;
use App\v1\Gadd as OldDd;
use App\Traits\ImportHelper;

class ImportVarRekomendasi extends Import 
{

    use ImportHelper;

    protected $status = ['normal','waspada','siaga','awas']; 

    public function __construct()
    {
        $this->old = OldDd::with('normal','waspada','siaga','awas')->select('ga_code')->get();
    }

    public function __invoke()
    {
        $this->old->each(function ($item,$key) {
            $this->setItem($item)
                ->createRekomendasi();
        });

        $data = $this->data
            ? [ 'success' => 1, 'text' => 'Var Rekomendasi', 'message' => 'Var Rekomendasi berhasil diperbarui', 'count' => VarRekomendasi::count() ] 
            : [ 'success' => 0, 'text' => 'Var Rekomendasi', 'message' => 'DVar Rekomendasi gagal diperbarui', 'count' => 0 ];

        $this->sendNotif($data);

        return response()->json($this->status);
    }

    protected function createRekomendasi()
    {

        foreach ($this->status as $value) {
            if (!empty($this->item->$value)) {
                try {
                    $create = VarRekomendasi::firstOrCreate(
                        [
                            'code_id' => $this->item->ga_code,
                            'status' => $this->item->$value->cu_status
                        ],
                        [
                            'rekomendasi' => $this->item->$value->var_rekom,
                            'created_at' => $this->item->$value->var_data_date
                        ]
                    );
    
                    if ($create) {
                        $this->data = true;
                    }
                }
    
                catch (Exception $e) {
                    $this->sendError($e);
                }
            }
        }

        return $this;
    }

}