<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\User;
use App\VarVisual;
use App\VarAsap;

class TesController extends Controller
{
    public function __construct(Request $request)
    {

        
        // dd(\App\VarVisual::select('id')->where('noticenumber_id','IYA1201604212400')->first()->id);
        // return view('tes.index');

    }

    public function index()
    {
        //SUM collection
        $sum = \App\EqTej::sum('jumlah');
        return $sum;

        //hasone
        $visual = VarVisual::find(2)->asap->wasap;
        return $visual;

        //belongsto
        return $asap = VarAsap::find(2)->visual;
        

    }

    public function imageCrop()
    {
        
        return view('tes.imageCrop');

    }

    public function imageCropPost(Request $request)
    {
        
        $data = $request->image;


        list($type, $data) = explode(';', $data);

        list(, $data)      = explode(',', $data);


        $data = base64_decode($data);
        
        $image_name= time().'.png';

        $path = public_path() . "/upload/" . $image_name;

        Storage::disk('user')->put($image_name, $data);
        $url = Storage::disk('user')->url($image_name);
        // file_put_contents($path, $data);


        return response()->json(['url'=>$url]);
    }
    
}
