<?php

namespace App\Http\Controllers\Import;

use Illuminate\Http\Request;
use App\MagmaVar;
use App\VarVisual;
use App\VarAsap;
use App\v1\MagmaVar as OldVar;
use App\Traits\ImportHelper;

class ImportVarVisual extends Import
{
    use ImportHelper;

    protected $obscode, $noticenumber, $visualasap;

    public function __construct()
    {
        $this->old = OldVar::select(
                'no','ga_code','var_image','var_image_create',
                'var_issued','var_source','var_noticenumber','var_visibility',
                'var_asap','var_tasap_min','var_tasap','var_wasap',
                'var_intasap','var_tekasap','var_viskawah')
            ->whereBetween('no',[$this->startNo('visuals'),$this->endNo('var')])
            ->orderBy('no','asc');
    }

    public function __invoke()
    {
        $this->old->chunk(5000, function($items) {
            foreach ($items as $key => $item) {
                $this->setItem($item)
                    ->createVarVisual()
                    ->createVarAsap();
            }
        });

        $this->sendNotif(
            [
                'text' => 'Data Visual',
                'message' => 'Data Visual berhasil diperbarui',
                'count' => VarVisual::count()
            ] 
        );

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
            $create = VarVisual::firstOrCreate(
                [   
                    'noticenumber_id'   => $this->noticenumber
                ],
                [   'visibility'        => $this->item->var_visibility,
                    'visual_asap'       => $this->item->var_asap,
                    'visual_kawah'      => $this->item->var_viskawah
                ]   
            );

            if ($create) {
                $this->data = $this->tempTable('visuals',$no)
                    ? [ 'success' => 1, 'message' => 'Data Visual berhasil diperbarui', 'count' => VarVisual::count() ] 
                    : [ 'success' => 0, 'message' => 'Data Visual gagal diperbarui', 'count' => 0 ];
            }

            $this->visualasap = $this->item->var_asap;

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