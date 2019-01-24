<?php

namespace App\Http\Controllers\api\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\v1\PressRelease;
use App\Http\Resources\v1\PressResource;
use App\Http\Resources\v1\PressCollection;

class PressController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $vars = PressRelease::orderBy('datetime','desc')->paginate(10);
        return new PressCollection($vars);
    }
}
