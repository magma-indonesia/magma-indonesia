<?php

namespace App\Http\Controllers\api\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\v1\MagmaVen;
use App\Http\Resources\v1\MagmaVenResource;
use App\Http\Resources\v1\MagmaVenCollection;

class MagmaVenController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $vars = MagmaVen::with('gunungapi:ga_code,ga_nama_gapi,ga_elev_gapi')
                    ->paginate(5);
        return new MagmaVenCollection($vars);
    }
}
