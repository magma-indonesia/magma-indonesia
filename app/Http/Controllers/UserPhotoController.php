<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

use App\User;

class UserPhotoController extends Controller
{
    private function quality($photo,$quality)
    {
        return $quality == 'high' 
            ? Storage::disk('user')->get($photo)
            : Storage::disk('user-thumb')->get($photo);
    }

    public function photo($id = null, $quality = null)
    {
        $user = User::findOrFail($id);
        $photo = optional($user->photo)->filename;

        if ($photo)
        {

            $file = $this->quality($photo,$quality);          
            return response($file, 200)->header('Content-Type', 'image/jpg');
                    
        }

        $file = Storage::disk('public')->get('thumb/user.png');
        return response($file, 200)->header('Content-Type', 'image/png');
    }
}
