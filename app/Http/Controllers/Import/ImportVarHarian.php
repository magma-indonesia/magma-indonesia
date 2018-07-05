<?php

namespace App\Http\Controllers\Import;

use Illuminate\Http\Request;
use App\MagmaVar;
use App\Gadd;
use App\VarDaily;
use App\v1\MagmaVar as OldVar;
use App\Traits\ImportHelper;

class ImportVarHarian extends Import
{
    use ImportHelper;

    protected $obscode, $noticenumber;

    public function __construct()
    {
        $this->old = Gadd::with('latestVar')
            ->whereNotIn('code',['TEO','SBG'])
            ->get();
    }

    public function __invoke()
    {
        $this->old->each(function ($item, $key) {
            $this->setItem($item)->updateDaily();
        });

        $data = $this->data
            ? [ 'success' => 1, 'text' => 'Data Harian', 'message' => 'Data Harian berhasil diperbarui', 'count' => VarDaily::count() ] 
            : [ 'success' => 0, 'text' => 'Data Harian', 'message' => 'Data Harian gagal diperbarui', 'count' => 0 ];

        $this->sendNotif($data);

        return response()->json($this->status);
    }

    public function updateDaily()
    {
        try {
            $create = VarDaily::updateOrCreate(
                ['code_id'  => $this->item->code],
                ['noticenumber_id' => $this->item->latestVar->noticenumber]
            );

            $this->data = $create ? true : false;
        }

        catch (Exception $e) {
            $this->sendError($e);
        }

        return $this;
    }
}