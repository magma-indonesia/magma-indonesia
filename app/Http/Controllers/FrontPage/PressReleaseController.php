<?php

namespace App\Http\Controllers\FrontPage;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Home\PressReleaseIndexRequest;
use App\PressRelease;
use App\Services\PressReleaseService;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class PressReleaseController extends Controller
{
    /**
     * Filter Press Release
     *
     * @param Request $request
     * @param Builder $query
     * @return Builder
     */
    public function filter(Request $request, Builder $query): Builder
    {
        if ($request->has('tag')) {
            $query->filterByTag($request->tag);
        }

        if ($request->has('volcano')) {
            $query->filterByVolcanoCode($request->volcano);
        }

        if ($request->has('category')) {
            switch ($request->category) {
                case 'gunung-api':
                    $query->where('gunung_api', 1);
                    break;
                case 'gerakan-tanah':
                    $query->where('gerakan_tanah', 1);
                    break;
                case 'gempa-bumi':
                    $query->where('gempa_bumi', 1);
                    break;
                case 'tsunami':
                    $query->where('tsunami', 1);
                    break;
                default:
                    $query->where('lainnya', $request->value);
                    break;
            }
        }

        return $query;
    }

    /**
     * Undocumented function
     *
     * @param Request $request
     * @return Paginator
     */
    public function cacheResponseIndex(Request $request): Paginator
    {
        $pressReleases = PressRelease::query();

        $pressReleases->with('tags:name,slug', 'gunungApi:code,name')->select([
            'id', 'judul', 'slug', 'no_surat', 'datetime',
            'gunung_api', 'gerakan_tanah', 'gempa_bumi', 'tsunami',
            'lainnya', 'code', 'short_deskripsi', 'hit'
        ])->where('is_published', 1)
        ->orderBy('datetime', 'desc');

        return $this->filter($request, $pressReleases)->simplePaginate(8);
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
        // return $this->cacheResponseIndex($request);

        return view('home.press-release.index', [
            'pressReleases' => $this->cacheResponseIndex($request),
        ]);

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
