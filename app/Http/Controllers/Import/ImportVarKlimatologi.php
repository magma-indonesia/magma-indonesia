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

    public function __construct()
    {
        ini_set('max_execution_time', 1200);
        $this->old = OldVar::select(
                'no','ga_code','var_noticenumber','var_source',
                'var_cuaca','var_curah_hujan',
                'var_suhumin','var_suhumax',
                'var_kelembabanmin','var_kelembabanmax',
                'var_tekananmin','var_tekananmax',
                'var_kecangin','var_arangin')
            ->whereBetween('no',[$this->startNo('klima'),$this->endNo('var')])
            ->orderBy('no','asc');
    }

    public function __invoke()
    {
        $this->old->chunk(5000, function ($items) {
            foreach ($items as $key => $item) {
                $this->setItem($item)->createKlimatologi();
            }
        });

        $this->sendNotif(
            [
                'text' => 'Data Klimatologi',
                'message' => 'Data Klimatologi berhasil diperbarui',
                'count' => VarKlimatologi::count()
            ] 
        );

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
            $create = VarKlimatologi::firstOrCreate(
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
                $this->data = $this->tempTable('klima',$no)
                    ? [ 'success' => 1, 'message' => 'Data Klimatologi berhasil diperbarui', 'count' => VarKlimatologi::count() ] 
                    : [ 'success' => 0, 'message' => 'Data Klimatologi gagal diperbarui', 'count' => 0 ];
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