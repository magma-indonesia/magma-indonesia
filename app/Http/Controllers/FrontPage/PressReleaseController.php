<?php

namespace App\Http\Controllers\FrontPage;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\PressRelease;

class PressReleaseController extends Controller
{
    public function show(string $id, string $slug)
    {
        return PressRelease::with('tags', 'press_release_files')
            ->where('id', $id)->where('slug', $slug)->firstOrFail();
    }
}
