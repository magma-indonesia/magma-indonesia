<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\DraftMagmaVar as Draft;
use App\MagmaVar;

class MagmaVarPhotoController extends Controller
{
    /**
     * Adding middleware for protecttion
     * 
     * @return boolean
     */
    public function __construct()
    {
        $this->middleware('auth'); 
    }

    protected function showDraft($request, $id)
    {
        $var = Draft::findOrFail($id);

        $filename = $request->has('others') ?
                        $var->var_visual['foto_lainnya'][$request->others] :
                        $var->var_visual['foto'];

        $photo = Storage::disk('temp')->path('var/'.$filename);
        return response()->file($photo);
    }

    protected function showOthers($id)
    {

        return response()->file($photo);
    }

    protected function showVar($id)
    {
        return $var = MagmaVar::with('visual')->where('noticenumber',$id)->firstOrFail();
    }

    /**
     * Show pictures from Draft Laporan
     * 
     * @return boolean
     */
    public function show(Request $request, $id, $draft = null)
    {
        return $draft == 'draft' ? 
                $this->showDraft($request, $id) : 
                $this->showVar($id);
    }
}
