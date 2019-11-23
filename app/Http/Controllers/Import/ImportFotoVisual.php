<?php

namespace App\Http\Controllers\Import;

use App\VarVisual;
use Illuminate\Http\Request;
use Exception;
use Image;
use Ping;
use Illuminate\Support\Facades\Storage;
use App\Traits\ImportHelper;

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

        $this->old = VarVisual::with('var:noticenumber,code_id,status')
            ->select('id','noticenumber_id','filename_0','filename_3','file_old')
            ->whereBetween('id',[$this->start_no, $this->end_no])
            ->orderBy('id');

        $this->old->chunk(500, function($visuals) {
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
        if ($visual->file_old) {

            $health = Ping::check($visual->file_old);

            if ($health == 200) {

                try {
                    $image = Image::make($visual->file_old);    
                } 
    
                catch (Exception $e) {
                    $visual->file_old = null;
                    $visual->save();
                    return $this;
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

            $visual->file_old = null;
            $visual->save();
            return $this;
        }

        return $this;
    }

}
