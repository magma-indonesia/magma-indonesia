<?php

namespace App\Http\Controllers\Import;

use Illuminate\Http\Request;
use App\MagmaVar;
use App\VarKlimatologi;
use App\v1\MagmaVar as OldVar;
use App\Traits\ImportHelper;

class ImportVarKlimatologi extends Import
{
    use ImportHelper;
    
    protected $obscode, $noticenumber;

    public function __construct(Request $request)
    {
        ini_set('max_execution_time', 1200);
    }

    public function import(Request $request)
    {
        $this->start_no = $request->has('start') ? $request->start : $this->startNo('klima');
        $this->end_no = $request->has('end') ? $request->end : $this->endNo('var');

        $this->old = OldVar::select(
            'no','ga_code','var_noticenumber','var_source',
            'var_cuaca','var_curah_hujan',
            'var_suhumin','var_suhumax',
            'var_kelembabanmin','var_kelembabanmax',
            'var_tekananmin','var_tekananmax',
            'var_kecangin','var_arangin')
        ->whereBetween('no',[$this->start_no,$this->end_no])
        ->orderBy('no','asc');
        
        $this->old->chunk(5000, function ($items) {
            foreach ($items as $key => $item) {
                $this->setItem($item)->createKlimatologi();
            }
        });

        $data = $this->data
                ? [ 'success' => 1, 'text' => 'Data Klimatologi', 'message' => 'Data Klimatologi berhasil diperbarui', 'count' => VarKlimatologi::count() ] 
                : [ 'success' => 0, 'text' => 'Data Klimatologi', 'message' => 'Data Klimatologi gagal diperbarui', 'count' => 0 ];

        $this->sendNotif($data);

        return response()->json($this->status);
    }

    protected function createKlimatologi()
    {
        $no = $this->item->no;
        $gacode = $this->item->ga_code;
        $var_source = $this->item->var_source;
        $this->obscode = $this->obscode($gacode,$var_source);
        $this->noticenumber = $this->obscode.$this->item->var_noticenumber;

        try {
            $create = VarKlimatologi::updateOrCreate(
                [
                    'noticenumber_id'   => $this->noticenumber
                ],
                [
                    'cuaca' => $this->item->var_cuaca,
                    'curah_hujan' => $this->item->var_curah_hujan,
                    'kecangin' => $this->item->var_kecangin,
                    'arahangin' => $this->item->var_arangin,
                    'suhumin' => $this->item->var_suhumin,
                    'suhumax' => $this->item->var_suhumax,
                    'lembabmin' => $this->item->var_kelembabanmin,
                    'lembabmax' => $this->item->var_kelembabanmax,
                    'tekmin' => $this->item->var_tekananmin,
                    'tekmax' => $this->item->var_tekananmax,
                ]
            );

            if ($create) {
                $this->data = $this->tempTable('klima',$no);
            }
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