<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Gadd;
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
        return $var = \App\MagmaVar::with('pj')
            ->where('noticenumber','AGU1201806290000')
            ->firstOrFail();
        //has many
        $gadd = Gadd::where('code','AGU')->first();
        $status = $gadd->status->first()->deskriptif;
        return $status;

        //get filename
        $user = User::FindOrFail(auth()->user()->id);
        $photo = $user->photo->filename;
        return $photo;
        
        //SUM collection
        $sum = \App\EqTej::sum('jumlah');
        return $sum;

        //hasone
        $visual = VarVisual::find(2)->asap->wasap;
        return $visual;

        //belongsto
        return $asap = VarAsap::find(2)->visual;
        

    }

    public function getFile($id)
    {
        $file = Storage::disk('user')->get('gZY9ubeGtTUBwx7DvvOXxifncBC6W8NlBYmSihDV.png');
        return response($file, 200)->header('Content-Type', 'image/png');
        
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
