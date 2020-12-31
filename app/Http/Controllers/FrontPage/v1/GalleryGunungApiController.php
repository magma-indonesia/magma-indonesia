<?php

namespace App\Http\Controllers\FrontPage\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Jobs\UpdateGalleryStatistik;
use App\v1\Gadd;
use App\v1\MagmaVar;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;

class GalleryGunungApiController extends Controller
{

    protected function filterdGallery(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'code' => 'required|size:3|exists:magma.ga_dd,ga_code',
            'start' => 'required|date_format:Y-m-d|before_or_equal:'.$request->end,
            'end' => 'required|date_format:Y-m-d|before_or_equal:'.now()->format('Y-m-d'),
        ]);

        if ($validator->fails()) {
            abort(404);
        }

        return MagmaVar::select('no','ga_code', 'ga_nama_gapi', 'var_data_date', 'var_image', 'var_image_create', 'periode')
            ->where('var_visibility', 'like', '%Jelas%')
            ->where('ga_code', $request->code)
            ->whereBetween('var_data_date', [$request->start, $request->end])
            ->orderBy('var_data_date', 'desc')
            ->orderBy('periode', 'desc')
            ->paginate(12);

    }

    protected function nonFilteredGallery()
    {
        return MagmaVar::select('no', 'ga_code', 'ga_nama_gapi', 'var_data_date', 'var_image', 'var_image_create','periode')
            ->where('var_visibility', 'like', '%Jelas%')
            ->orderBy('var_data_date', 'desc')
            ->orderBy('periode', 'desc')
            ->paginate(12);
    }

    public function index(Request $request, $q = null)
    {
        $gadds = Cache::remember('v1/home/gadds', 120, function () {
            return Gadd::select('ga_code', 'ga_nama_gapi')
            ->orderBy('ga_nama_gapi')
            ->get();
        });

        $vars = $q === 'q' ? $this->filterdGallery($request)
                : $this->nonFilteredGallery();

        return view('v1.home.gallery-index', [
            'vars' => $vars,
            'gadds' => $gadds,
        ]);
    }

    public function statistic(Request $request)
    {
        UpdateGalleryStatistik::dispatch($request->no);
    }
}
