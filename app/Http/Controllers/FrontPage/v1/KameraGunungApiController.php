<?php

namespace App\Http\Controllers\FrontPage\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\KameraGunungApi;
use App\Gadd;
use Karlmonson\Ping\Facades\Ping;
use Intervention\Image\Facades\Image;

class KameraGunungApiController extends Controller
{
    protected function filteredCCTV($code)
    {
        return KameraGunungApi::with('gunungapi:code,name')
                ->where('publish',1)
                ->where('code',$code)
                ->orderBy('code')
                ->get();
    }

    protected function nonFilteredCCTV()
    {
        return KameraGunungApi::with('gunungapi:code,name')
                ->where('publish',1)
                ->orderBy('code')
                ->get();
    }

    public function index($code = null)
    {
        $health = Ping::check(config('app.cctv_url'));

        if ($health == 200)
        {
            $gadds = Gadd::select('code','name')
                        ->whereHas('cctv', function ($query) {
                            $query->where('publish',1);
                        })
                        ->withCount('cctv')
                        ->get();
                        
            $cctvs = $code === null ? $this->nonFilteredCCTV() : $this->filteredCCTV($code);

            if ($cctvs->isEmpty())
                abort(404);
                    
            $cctvs->each(function ($item, $key) {
                try {
                    $image = Image::make($item->full_url)
                                ->widen(150)->stream('data-url');
                    $item['image'] = $image;
                }

                catch(\Exception $e) {
                    $item['image'] = null;
                }
            });

            return view('v1.home.cctv',compact('cctvs','gadds'));
        }

        abort(500, 'Server CCTV sedang Off');
    }

    public function show(Request $request)
    {
        $health = Ping::check(config('app.cctv_url'));

        if ($health == 200)
        {
            $cctv = KameraGunungApi::with('gunungapi:code,name')
                        ->where('publish',1)
                        ->where('uuid',$request->uuid)
                        ->firstOrFail();

            try {
                $image = Image::make($cctv->full_url)->stream('data-url');
                $cctv->setAttribute('image', $image);
            }

            catch (\Exception $e) {
                $cctv->setAttribute('image', null);
            }

            $cctv->increment('hit');
            
            return view('v1.home.cctv-show',compact('cctv'));

        }

        abort(500, 'Server CCTV sedang Off');
    }
}
