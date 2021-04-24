<?php

namespace App\Http\Controllers\Import;

use App\VarVisual;
use Illuminate\Http\Request;
use Exception;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;
use App\Traits\ImportHelper;

class ImportFotoVisual extends Import
{
    use ImportHelper;

    public function __construct()
    {
        ini_set('max_execution_time', 1200);
        ini_set('memory_limit', '512M');
    }

    public function import(Request $request)
    {
        $start = $request->has('start') ? $request->start : $this->startNo('foto_vis');
        $end = $request->has('end') ? $request->end : VarVisual::orderByDesc('id')->first()->id;

        $this->data = $this->tempTable('foto_vis',$start);

        $this->old = VarVisual::with('var:noticenumber,code_id,status')
            ->select('id','noticenumber_id','filename_0','filename_3','file_old')
            ->whereBetween('id',[$start, $end])
            ->orderBy('id');

        $this->old->chunk(500, function($visuals) {
            foreach ($visuals as $key => $visual) {
                if ($visual->file_old) {
                    $this->downloadFotoVisual($visual);
                }
                $this->data = $this->tempTable('foto_vis',$visual->id);
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
            $url = str_replace('https://magma.vsi.esdm.go.id','http://172.24.24.4',$visual->file_old);
            $image = Image::make($url);

            if ($image->width() > 800) {
                $image = $image->widen(800);
            }

            $filename = $visual->noticenumber_id.'_'.uniqid().'.png';

            if (Storage::disk('var_visual')->put($visual->var->code_id.'/'.$filename, $image->stream()))
            {
                Storage::disk('var_visual')->put($visual->var->code_id.'/thumbs/'.$filename, $image->widen(150)->stream());
    
                $visual->filename_0 = $filename;
                $visual->filename_3 = null;
                $visual->save();
    
                $image->destroy();
        
                return $this;
            }
        }

        catch (Exception $e) {
            $visual->file_old = null;
            $visual->filename_3 = null;
            $visual->save();
            return $this;
        }

    }

}
