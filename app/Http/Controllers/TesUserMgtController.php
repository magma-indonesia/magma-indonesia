<?php

namespace App\Http\Controllers;

use App\TesUserMgt;
use Illuminate\Http\Request;
use App\Http\Resources\UserResource;
use App\Http\Resources\UserCollection;

class TesUserMgtController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = TesUserMgt::paginate(30);
        return $users;
    }
}
