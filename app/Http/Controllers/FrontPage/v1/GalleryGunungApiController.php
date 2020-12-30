<?php

namespace App\Http\Controllers\FrontPage\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\v1\MagmaVar;

class GalleryGunungApiController extends Controller
{
    public function index()
    {
        $vars = MagmaVar::select('no', 'ga_nama_gapi', 'var_data_date','var_image','periode')
                ->orderBy('var_data_date','desc')
                ->orderBy('periode','desc')
                ->where('var_visibility','like','%Jelas%')
                ->paginate(30);

        return view('v1.home.gallery-index', ['vars' => $vars]);
    }

    public function show(Request $request)
    {
        
    }
}
