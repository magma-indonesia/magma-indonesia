<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TestController extends Controller
{
    public function index(Request $request)
    {
        return dd($_SERVER, $request->header(), $request->headers->get('X-Real-IP'), );
    }
}
