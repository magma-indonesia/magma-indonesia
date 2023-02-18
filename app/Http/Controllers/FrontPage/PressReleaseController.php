<?php

namespace App\Http\Controllers\FrontPage;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Home\PressReleaseIndexRequest;
use App\PressRelease;
use App\Services\PressReleaseService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class PressReleaseController extends Controller
{
    public function filter(Request $request, Builder $query): Builder
    {
        if ($request->has('tag')) {
            $query->whereHas(
                'tags',
                function ($tag) use ($request) {
                    $tag->where('slug', $request->tag);
                },
            );
        }

        if ($request->has('volcano')) {
            $query->whereHas(
                'gunungApi',
                function ($gunungApi) use ($request) {
                    $gunungApi->where('code', $request->volcano);
                },
            );
        }

        if ($request->has('gunung-api')) {
        }

        if ($request->has('gerakan-tanah')) {
        }

        if ($request->has('gempa-bumi')) {
        }

        if ($request->has('tsunami')) {
        }

        if ($request->has('lainnya')) {
        }

        return $query;
    }

    /**
     * Undocumented function
     *
     * @param Request $request
     * @return void
     */
    public function cacheResponseIndex(Request $request): Collection
    {
        $pressReleases = PressRelease::query();

        $pressReleases->with('tags:name,slug', 'gunungApi:code,name')->select([
            'id', 'judul', 'slug', 'no_surat',
            'gunung_api', 'gerakan_tanah', 'gempa_bumi', 'tsunami',
            'lainnya', 'code', 'short_deskripsi', 'hit'
        ])->where('is_published', 1);

        return $this->filter($request, $pressReleases)->get();
    }

    /**
     * Get list of Press Reelase Index
     *
     * @return \Illuminate\Http\Response
     */
    public function index(
        PressReleaseIndexRequest $request,
        PressReleaseService $pressReleaseService)
    {
        return $this->cacheResponseIndex($request);

        return PressRelease::with([
            'peta_krbs:code,tahun,filename,size,medium_size,large_size',
            'gunungApi:code,name',
            'press_release_files',
            'tags:name,slug',
        ])->get();
    }

    /**
     * Undocumented function
     *
     * @param string $id
     * @param string $slug
     * @param PressReleaseService $pressReleaseService
     * @return void
     */
    public function show(
        string $id,
        string $slug,
        PressReleaseService $pressReleaseService)
    {
        return view('home.press-release.show', $pressReleaseService->cacheResponseShow($id, $slug, true));
    }
}
