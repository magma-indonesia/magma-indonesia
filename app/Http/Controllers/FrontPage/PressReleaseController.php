<?php

namespace App\Http\Controllers\FrontPage;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\PressRelease;
use App\PressReleaseFile;
use Illuminate\Support\Facades\Cache;

class PressReleaseController extends Controller
{
    /**
     * Get thumbnail URL
     *
     * @param PressRelease $pressRelease
     * @return PressReleaseFile|null
     */
    public function thumbnailUrl(PressRelease $pressRelease): ?string
    {
        $pressReleaseFile = $pressRelease->press_release_files->whenNotEmpty(function ($pressReleaseFiles) {
            return $pressReleaseFiles->when($pressReleaseFiles->contains('collection', 'petas'), function ($pressReleaseFiles) {
                return $pressReleaseFiles->where('collection', 'petas')->first();
            })->when($pressReleaseFiles->contains('collection', 'gambars'), function ($pressReleaseFiles) {
                return $pressReleaseFiles->where('collection', 'gambars')->first();
            });
        });

        return collect($pressReleaseFile)->isEmpty() ? null : $pressReleaseFile['thumbnail'];
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
            'gunungApi:code,name',
            'tags:name,slug',
            'press_release_files'
        ])->where('id', $id)
            ->where('slug', $slug)
            ->firstOrFail();
    }

    /**
     * Cache press release show
     *
     * @param string $id
     * @param string $slug
     * @return array
     */
    public function cacheResponseShow(string $id, string $slug): array
    {
        return Cache::tags(['home:press-release'])
            ->rememberForever("home:press-release:$id:$slug", function () use ($id, $slug) {
                $pressRelease = $this->pressReleaseShow($id, $slug);

                return [
                    'pressRelease' => $pressRelease,
                    'thumbnail' => $this->thumbnailUrl($pressRelease)
                ];
            });
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
        return view('home.press-release.show', $this->cacheResponseShow($id, $slug));
    }
}
