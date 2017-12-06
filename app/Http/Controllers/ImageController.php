<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;


class ImageController extends Controller
{
    //
    public function user(Request $request, $id)
    {
        $exists = Storage::disk('local')->get('press/photo/gZY9ubeGtTUBwx7DvvOXxifncBC6W8NlBYmSihDV.png');

        return Image::make($exists)->response('png');
    }
}
