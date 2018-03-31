<?php

namespace App\Http\Controllers;

use App\TesUserMgb;
use Illuminate\Http\Request;
use App\Http\Resources\UserResource;
use App\Http\Resources\UserCollection;

class TesUserMgbController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = TesUserMgb::paginate(30);
        return $users;
    }
}
