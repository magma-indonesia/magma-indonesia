<?php

namespace App\Services;

use App\PressRelease;
use Illuminate\Http\Request;

class PressReleaseService
{
    /**
     * Store and return Press release model
     *
     * @param Request $request
     * @return PressRelease
     */
    public function storePressRelease(Request $request): PressRelease
    {
        $pressRelease = PressRelease::firstOrCreate([
            'slug' => $request->slug,
            'datetime' => $request->datetime ?? now(),
        ],[
            'judul' => $request->judul,
            'no_surat' => $request->no_surat,
            'gunung_api' => in_array('gunung_api', $request->categories),
            'gerakan_tanah' => in_array('gerakan_tanah', $request->categories),
            'gempa_bumi' => in_array('gempa_bumi', $request->categories),
            'tsunami' => in_array('tsunami', $request->categories),
            'lainnya' => $request->lainnnya,
            'code' => $request->code,
            'deskripsi' => $request->deskripsi,
            'nip' => request()->user()->nip,
        ]);

        $pressRelease->tags()->attach($request->tags);

        return $pressRelease;
    }
}