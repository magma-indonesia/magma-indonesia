<?php

namespace App\Http\Controllers\Import;

use Illuminate\Http\Request;
use App\MagmaVar;
use App\VarVisual;
use App\VarAsap;
use App\v1\MagmaVar as OldVar;
use App\Traits\ImportHelper;
use Exception;

class ImportVarVisual extends Import
{
    use ImportHelper;

    protected $obscode, $noticenumber, $visualasap;

    public function __construct(Request $request)
    {
        ini_set('max_execution_time', 1200);
    }

    public function import(Request $request)
    {
        $this->start_no = $request->has('start') ? $request->start : $this->startNo('visuals');
        $this->end_no = $request->has('end') ? $request->end : $this->endNo('var');

        $this->old = OldVar::select(
            'no','ga_code','var_image','var_image_create',
            'var_issued','var_source','var_noticenumber','var_visibility',
            'var_asap','var_tasap_min','var_tasap','var_wasap',
            'var_intasap','var_tekasap','var_viskawah')
        ->whereBetween('no',[$this->start_no,$this->end_no])
        ->orderBy('no','asc');

        $this->old->chunk(5000, function($items) {
            foreach ($items as $key => $item) {
                $this->setItem($item)
                    ->createVarVisual()
                    ->createVarAsap();
            }
        });

        $data = $this->data
                ? [ 'success' => 1, 'text' => 'Data Visual', 'message' => 'Data Visual berhasil diperbarui', 'count' => VarVisual::count() ] 
                : [ 'success' => 0, 'text' => 'Data Visual', 'message' => 'Data Visual gagal diperbarui', 'count' => 0 ];

        $this->sendNotif($data);

        return response()->json($this->status);
    }

    protected function createVarVisual()
    {
        $no = $this->item->no;
        $gacode = $this->item->ga_code;
        $var_source = $this->item->var_source;
        $this->obscode = $this->obscode($gacode,$var_source);
        $this->noticenumber = $this->obscode.$this->item->var_noticenumber;

        try {
            $create = VarVisual::updateOrCreate(
                [   
                    'noticenumber_id'   => $this->noticenumber
                ],
                [   'visibility'        => $this->item->var_visibility,
                    'visual_asap'       => $this->item->var_asap,
                    'visual_kawah'      => $this->item->var_viskawah,
                    'file_old'          => str_replace('http://','https://',$this->item->var_image)
                ]   
            );

            if ($create) {
                $this->data = $this->tempTable('visuals',$no);
            }

            $this->visualasap = $this->item->var_asap;

            return $this;
        }

        catch (Exception $e) {
            $this->sendError($e);
        }
    }

    protected function createVarAsap()
    {
        if ($this->visualasap == 'Teramati') {

            $visual_id = VarVisual::select('id')
                ->where('noticenumber_id',$this->noticenumber)
                ->first()->id;

            $create = VarAsap::firstOrCreate(
                    [
                        'var_visual_id' => $visual_id
                    ],
                    [   
                        'tasap_min' => $this->item->var_tasap_min,
                        'tasap_max' => $this->item->var_tasap,
                        'wasap' => $this->item->var_wasap,
                        'intasap' => $this->item->var_intasap,
                        'tekasap' => $this->item->var_tekasap
                    ]
                );
        }

        return $this;
    }
}