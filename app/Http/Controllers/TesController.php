<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\VarVisual;
use App\VarAsap;

class TesController extends Controller
{
    public function __construct(Request $request)
    {

        
        // dd(\App\VarVisual::select('id')->where('noticenumber_id','IYA1201604212400')->first()->id);
        // return view('tes.index');

    }

    public function index(){
        
        //hasone
        $visual = VarVisual::find(2)->asap->wasap;
        return $visual;

        //belongsto
        return $asap = VarAsap::find(2)->visual;
        
        
        

    }
}
