<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Mounts\Mounts;

class MountsController extends Controller
{
    public function index()
    {
        return Mounts::all();
    }
}
