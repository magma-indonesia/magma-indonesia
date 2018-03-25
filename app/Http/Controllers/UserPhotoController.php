<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

use App\User;

class UserPhotoController extends Controller
{
    public function photo($id = null)
    {
        $photo = optional(auth()->user()->photo)->filename;

        if ($photo)
        {

            $file = Storage::disk('user-thumb')->get($photo);
            return response($file, 200)->header('Content-Type', 'image/jpg');
                    
        }

        $file = Storage::disk('user-thumb')->get('user.png');
        return response($file, 200)->header('Content-Type', 'image/png');
    }
}
