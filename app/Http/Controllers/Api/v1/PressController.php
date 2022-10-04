<?php

namespace App\Http\Controllers\Api\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\v1\PressRelease;
use App\Http\Resources\v1\PressCollection;
use App\Http\Resources\v1\PressShowResource;

class PressController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $presses = PressRelease::orderBy('datetime','desc')->paginate(10);
        return new PressCollection($presses);
    }

    public function show($id)
    {
        $press = PressRelease::findOrFail($id);
        return new PressShowResource($press);
    }
}
