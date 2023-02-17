<?php

namespace App\Http\Controllers\FrontPage;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\PressRelease;
use App\PressReleaseFile;
use App\Tag;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class PressReleaseController extends Controller
{
    /**
     * Get image URL
     *
     * @param PressRelease $pressRelease
     * @return PressReleaseFile|null
     */
    public function imageUrl(PressRelease $pressRelease): ?PressReleaseFile
    {
        $pressReleaseFile = $pressRelease->press_release_files->whenNotEmpty(function ($pressReleaseFiles) {
            return $pressReleaseFiles->whereIn('collection', ['petas', 'gambars'])->first();
        });

        return $pressReleaseFile;
    }

    /**
     * Get Press Release based on id and slug
     *
     * @param string $id
     * @param string $slug
     * @return PressRelease
     */
    public function pressReleaseShow(string $id, string $slug): PressRelease
    {
        return PressRelease::with([
            'peta_krbs:code,tahun,filename,size,medium_size,large_size',
            'gunungApi:code,name',
            'press_release_files',
            'tags:name,slug',
        ])->where('id', $id)->where('slug', $slug)->firstOrFail();
    }

    /**
     * Cache press release show
     *
     * @param string $id
     * @param string $slug
     * @param boolean $enable
     * @return array
     */
    public function cacheResponseShow(string $id, string $slug, bool $disable = false): array
    {
        if ($disable) {
            Cache::tags(['home:press-release'])->flush();
        }

        return Cache::tags(['home:press-release'])
            ->rememberForever("home:press-release:$id:$slug", function () use ($id, $slug) {
                $pressRelease = $this->pressReleaseShow($id, $slug);
                $imageUrl = $this->imageUrl($pressRelease);

                return [
                    'pressRelease' => $pressRelease,
                    'cover' => $imageUrl ? $imageUrl->url : null,
                    'thumbnail' => $imageUrl ? $imageUrl->thumbnail : null,
                ];
            });
    }

    public function cacheResponseIndex(Request $request)
    {
        $pressReleases = PressRelease::query();

        if ($request->has('tag')) {
            $pressReleases->with([
                'tags'
            ]);
        }

        return [];
    }

    /**
     * Get list of Press Reelase Index
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
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
     * Show Press Release
     *
     * @param string $id
     * @param string $slug
     * @return \Illuminate\Http\Response
     */
    public function show(string $id, string $slug)
    {
        return view('home.press-release.show', $this->cacheResponseShow($id, $slug, true));
    }
}
