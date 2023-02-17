<?php

namespace App\Http\Controllers\FrontPage;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\PressRelease;
use App\PressReleaseFile;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class PressReleaseController extends Controller
{
    public function coverUrl(PressRelease $pressRelease): ?string
    {
        $pressReleaseFile = $this->imageUrl($pressRelease);

        return $pressReleaseFile->isEmpty() ? null : $pressReleaseFile['url'];
    }

    public function imageUrl(PressRelease $pressRelease): Collection
    {
        $pressReleaseFile = $pressRelease->press_release_files->whenNotEmpty(function ($pressReleaseFiles) {
            return $pressReleaseFiles->when($pressReleaseFiles->contains('collection', 'petas'), function ($pressReleaseFiles) {
                return $pressReleaseFiles->where('collection', 'petas')->first();
            })->when($pressReleaseFiles->contains('collection', 'gambars'), function ($pressReleaseFiles) {
                return $pressReleaseFiles->where('collection', 'gambars')->first();
            });
        });

        return collect($pressReleaseFile);
    }

    /**
     * Get thumbnail URL
     *
     * @param PressRelease $pressRelease
     * @return PressReleaseFile|null
     */
    public function thumbnailUrl(PressRelease $pressRelease): ?string
    {
        $pressReleaseFile = $this->imageUrl($pressRelease);

        return $pressReleaseFile->isEmpty() ? null : $pressReleaseFile['thumbnail'];
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
            'gunung_api:code,name',
            'tags:name,slug',
            'press_release_files',
            'peta_krbs:code,tahun,filename,size,medium_size,large_size'
        ])->where('id', $id)
        ->where('slug', $slug)
        ->firstOrFail();
    }

    /**
     * Cache press release show
     *
     * @param string $id
     * @param string $slug
     * @param boolean $enable
     * @return array
     */
    public function cacheResponseShow(string $id, string $slug, bool $disable = false)
    {
        if ($disable) {
            Cache::tags(['home:press-release'])->flush();
        }

        $pressRelease = $this->pressReleaseShow($id, $slug);

        return $pressRelease->press_release_files;

        return $this->imageUrl($pressRelease)->toArray();

        return [
            'pressRelease' => $pressRelease,
            'cover' => $this->coverUrl($pressRelease),
            'thumbnail' => $this->thumbnailUrl($pressRelease)
        ];

        return Cache::tags(['home:press-release'])
            ->rememberForever("home:press-release:$id:$slug", function () use ($id, $slug) {
                $pressRelease = $this->pressReleaseShow($id, $slug);

                return [
                    'pressRelease' => $pressRelease,
                    'cover' => $this->coverUrl($pressRelease),
                    'thumbnail' => $this->thumbnailUrl($pressRelease)
                ];
            });
    }

    public function index()
    {
        return PressRelease::with([
            'peta_krbs:code,tahun,filename,size,medium_size,large_size',
            'gunung_api:code,name',
            'press_release_files',
            'tags',
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
        return $this->cacheResponseShow($id, $slug, true);

        return view('home.press-release.show', $this->cacheResponseShow($id, $slug, true));
    }
}
