<?php

namespace App\Http\Controllers\FrontPage\v1;

Use App\Gadd;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\LiveSeismogram;
use Ping;
use Image;
use Storage;

class LiveSeismogramController extends Controller
{
    public function index()
    {
        $health = Ping::check(config('app.winston_host'));

        if ($health == 200)
        {
            $gadds = Gadd::whereHas('live_seismograms')
                        ->select('code','name')
                        ->orderBy('name')
                        ->with('live_seismograms.seismometer:id,scnl')
                        ->get();

            $gadds->each(function($gadd) {
                $gadd->live_seismograms->each(function ($live) {

                    try {
                        $path = Storage::disk('seismogram')->get('thumb_'.$live->filename);
                        $image = Image::make($path)->stream('data-url');
                        $live['image'] = $image;
                    }
    
                    catch(\Exception $e) {
                        $live['image'] = null;
                    }
                });
            });
            
            return view('v1.home.live-seismogram', compact('gadds'));

        }

        abort(500, 'Server Seismogram sedang Off');

    }

    public function show(Request $request)
    {
        $health = Ping::check(config('app.winston_host'));

        if ($health == 200)
        {
            $live = LiveSeismogram::with('gunungapi:code,name','seismometer:id,scnl')
                    ->whereId($request->uuid)
                    ->firstOrFail();

            try {
                $path = Storage::disk('seismogram')->get($live->filename);
                $image = Image::make($path)->stream('data-url');
                $live->setAttribute('image', $image);
            }

            catch (\Exception $e) {
                $live->setAttribute('image', null);
            }

            $live->seismometer->increment('hit');

            return view('v1.home.live-seismogram-show', compact('live'));
        }

        abort(500, 'Server Seismogram sedang Off');

    }
}
