<?php

namespace App\Http\Controllers\Demo\NusaGeomatika;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class LoginController extends Controller
{
    public function index()
    {
        return 'login index';
    }

    public function login(Request $request)
    {
        return 'login';
    }
}
