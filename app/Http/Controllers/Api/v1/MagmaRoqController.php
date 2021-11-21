<?php

namespace App\Http\Controllers\Api\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\v1\Api\MagmaRoqFilterRequest;
use App\Http\Resources\v1\MagmaRoqCollection;
use App\Http\Resources\v1\MagmaRoqResource;
use App\v1\MagmaRoq;
use Illuminate\Support\Facades\Cache;

class MagmaRoqController extends Controller
{
    public function index(Request $request)
    {
        $page = $request->has('page') ? $request->page : 1;

        $last = MagmaRoq::select('no', 'datetime_wib', 'roq_logtime')
            ->where('roq_tanggapan', 'YA')
            ->orderBy('datetime_wib', 'desc')
            ->first();

        $date = strtotime($last->roq_logtime);

        $roqs = Cache::remember('v1/api/home/gempa-bumi:' . $page . ':' . $date, 60, function () {
            return MagmaRoq::where('roq_tanggapan', 'YA')
                ->orderBy('datetime_wib', 'desc')
                ->paginate(15);
        });

        return new MagmaRoqCollection($roqs);
    }

    public function show($id)
    {
        $roq = Cache::remember('v1/api/home/gempa-bumi/' . $id, 60, function () use ($id) {
            return MagmaRoq::where('no', $id)->firstOrFail();
        });

        return new MagmaRoqResource($roq);
    }

    public function latest()
    {
        $roq = MagmaRoq::orderBy('datetime_wib', 'desc')->first();

        return new MagmaRoqResource($roq);
    }

    public function filter(MagmaRoqFilterRequest $request)
    {
        $validated = $request->validated();

        $roqs = MagmaRoq::where('roq_tanggapan', 'YA')
            ->whereBetween('datetime_wib', [
                $validated['start_date'],
                $validated['end_date']
            ])
            ->orderBy('datetime_wib', 'desc')
            ->paginate(15);

        return new MagmaRoqCollection($roqs);
    }
}
