<?php

namespace App\Services;

use App\PressRelease;
use Illuminate\Http\Request;

class PressReleaseService
{
    public function storePressRelease(Request $request)
    {
        $pressRelease = PressRelease::firstOrCreate([
            'slug' => $request->slug,
            'date' => $request->date,
        ],[

        ]);
    }
}