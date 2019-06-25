<?php

namespace App\Http\Controllers\Projects;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PeerController extends Controller
{
    public function index()
    {
        return view('projects.peer.index');
        return 'index';
    }
}
