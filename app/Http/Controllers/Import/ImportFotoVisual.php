<?php

namespace App\Http\Controllers\Import;

use App\VarVisual;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Traits\ImportHelper;
use Exception;
use Image;
use Illuminate\Support\Facades\Storage;

class ImportFotoVisual extends Import
{
    use ImportHelper;

    public function __construct(Request $request)
    {
        ini_set('max_execution_time', 1200);
        $this->start_no = $request->has('start') ? $request->start : $this->startNo('foto_vis');
        $this->end_no = $request->has('end') ? $request->end : VarVisual::orderByDesc('id')->first()->id;
    }

    public function import(Request $request)
    {
        $this->var_visuals = VarVisual::with('var:noticenumber,code_id,status')
            ->select('id','noticenumber_id','filename_3','file_old')
            ->whereBetween('id',[$this->start_no, $this->end_no])
            ->orderBy('id');

        $this->var_visuals->chunk(500, function($items) {
            foreach ($items as $key => $item) {
                $this->downloadFotoVisual($item);
            }
        });
        
        $data = $this->data
        ? [ 'success' => 1, 'text' => 'Foto Visual', 'message' => 'Foto Visual berhasil didownload', 'count' => VarVisual::count() ] 
        : [ 'success' => 0, 'text' => 'Foto Visual', 'message' => 'Foto Visual gagal diperbarui', 'count' => 0 ];

        $this->sendNotif($data);

        return response()->json($this->status);

    }

    protected function downloadFotoVisual($visual)
    {
        try {
            if ($visual->file_old) {
                $image = Image::make($visual->file_old);     
                $filename = sha1(uniqid()).'.png';
    
                if (Storage::disk('var_visual')->put($visual->var->code_id.'/'.$filename, $image->stream()))
                {
                    Storage::disk('var_visual')->put($visual->var->code_id.'/thumbs/'.$filename, $image->widen(150)->stream());
                    $this->updateVarVisual($visual, $filename);
                }   
            }

            return $this;
        }

        catch (Exception $e) {
            $visual->file_old = null;
            $visual->save();
            return $this;
        }
    }

    protected function updateVarVisual($visual, $filename)
    {
        $visual->filename_3 = $filename;
        if ($visual->save()) {
            $this->data = $this->tempTable('foto_vis',$visual->id);
        }

        return $this;
    }

}
