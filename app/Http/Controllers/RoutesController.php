<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

class RoutesController extends Controller
{
    public function index()
    {
        $routeCollection = Route::getRoutes()->get();
        return $routeCollection;
    }
}
