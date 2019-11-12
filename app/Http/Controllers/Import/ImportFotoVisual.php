<?php

namespace App\Http\Controllers\Import;

use App\TempTable;
use App\VarVisual;
use Illuminate\Http\Request;
use App\Traits\ImportHelper;
use Exception;
use Image;
use Illuminate\Support\Facades\Storage;

class ImportFotoVisual extends Import
{
    use ImportHelper;

    public function __construct()
    {
        ini_set('max_execution_time', 1200);
    }

    public function import(Request $request)
    {
        $this->start_no = $request->has('start') ? $request->start : $this->startNo('foto_vis');
        $this->end_no = $request->has('end') ? $request->end : VarVisual::orderByDesc('id')->first()->id;

        $this->var_visuals = VarVisual::with('var:noticenumber,code_id,status')
            ->select('id','noticenumber_id','filename_3','file_old')
            ->whereBetween('id',[1, 1])
            ->orderBy('id');

        $this->var_visuals->chunk(500, function($visuals) {
            foreach ($visuals as $key => $visual) {
                $this->downloadFotoVisual($visual)
                    ->tempTable('foto_vis',$visual->id);
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

                    $visual->filename_3 = $filename;
                    $visual->save();
            
                    return $this;
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

}
