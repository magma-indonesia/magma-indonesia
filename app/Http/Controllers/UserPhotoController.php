<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

use App\User;

class UserPhotoController extends Controller
{
    private function quality($filename,$quality)
    {
        return $quality == 'high' 
            ? Storage::disk('user')->path($filename)
            : Storage::disk('user-thumb')->path($filename);
    }

    public function photo($id = null, $quality = null)
    {
        $user = User::findOrFail($id);
        $filename = optional($user->photo)->filename;

        if ($filename)
        {
            $file = $this->quality($filename,$quality);          
            return response()->file($file);
        }

        $file = Storage::disk('public')->path('thumb/user.png');
        return response()->file($file);
    }
}
