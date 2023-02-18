<?php

namespace App\Services;

use App\PressRelease;
use App\v1\PressRelease as V1PressRelease;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class PressReleaseService
{
    /**
     * Get document URL
     *
     * @param PressRelease $pressRelease
     * @return Collection
     */
    public function fileUrl(PressRelease $pressRelease): Collection
    {
        $pressReleaseFile = $pressRelease->press_release_files->whenNotEmpty(function ($pressReleaseFiles) {
            return collect($pressReleaseFiles->whereIn('collection', ['files'])->first());
        });

        return collect($pressReleaseFile->toArray());
    }

    /**
     * Get image URL
     *
     * @param PressRelease $pressRelease
     * @return Collection
     */
    public function imageUrl(PressRelease $pressRelease): Collection
    {
        $pressReleaseFile = $pressRelease->press_release_files->whenNotEmpty(function ($pressReleaseFiles) {
            return collect($pressReleaseFiles->whereIn('collection', ['petas', 'gambars'])->first());
        });

        return collect($pressReleaseFile->toArray());
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
                    'cover' => $imageUrl->isNotEmpty() ? $imageUrl['url'] : null,
                    'thumbnail' => $imageUrl->isNotEmpty() ? $imageUrl['thumbnail'] : null,
                ];
            });
    }


    /**
     * Clear html tags for short description
     *
     * @param string $deskripsi
     * @return string
     */
    public function shortDeskripsi(string $deskripsi): string
    {
        return Str::limit(
            strip_tags(
                str_replace(
                    ['<br><br>', '<br>' , '&nbsp;'],
                    [' ', ' ', ''],
                    $deskripsi
                )
            ),
            275,
            '...'
        );
    }

    /**
     * Get first or create attributes in array
     *
     * @param Request $request
     * @return array
     */
    public function firstOrCreateAttributes(Request $request): array
    {
        return [
            'slug' => $request->slug,
            'datetime' => $request->datetime ?? now(),
        ];
    }

    /**
     * Fill attributes in array
     *
     * @param Request $request
     * @return array
     */
    public function fillAttributes(Request $request): array
    {
        return [
            'judul' => $request->judul,
            'no_surat' => $request->no_surat,
            'gunung_api' => in_array('gunung_api', $request->categories),
            'gerakan_tanah' => in_array('gerakan_tanah', $request->categories),
            'gempa_bumi' => in_array('gempa_bumi', $request->categories),
            'tsunami' => in_array('tsunami', $request->categories),
            'lainnya' => $request->lainnya,
            'code' => $request->code,
            'deskripsi' => $request->deskripsi,
            'short_deskripsi' => $this->shortDeskripsi($request->deskripsi),
            'is_published' => $request->is_published,
            'nip' => request()->user()->nip,
        ];
    }

    /**
     * Merge first or create attributes and fill attributes
     *
     * @param Request $request
     * @return array
     */
    public function mergeAttributes(Request $request): array
    {
        return array_merge(
            $this->firstOrCreateAttributes($request),
            $this->fillAttributes($request)
        );
    }

    /**
     * Clear cache
     *
     * @return void
     */
    public function clearCache(): void
    {
        Cache::tags(['home:press-release'])->flush();
    }

    /**
     * Store and return Press release model
     *
     * @param Request $request
     * @return PressRelease
     */
    public function storePressRelease(Request $request): PressRelease
    {
        $pressRelease = PressRelease::firstOrCreate(
            $this->firstOrCreateAttributes($request),
            $this->fillAttributes($request)
        );

        $pressRelease->tags()->attach($request->tags);

        $this->clearCache();

        return $pressRelease;
    }

    /**
     * Store to old Press Release
     *
     * @param PressRelease $pressRelease
     * @return V1PressRelease
     */
    public function storeToOldPressRelease(PressRelease $pressRelease): V1PressRelease
    {
        $pressRelease->load('press_release_files');

        $fotolink = $this->imageUrl($pressRelease);
        $file = $this->fileUrl($pressRelease);

        $oldPressRelease = V1PressRelease::firstOrCreate([
            'slug' => $pressRelease->slug,
            'datetime' => $pressRelease->datetime,
        ], [
            'judul' => $pressRelease->judul,
            'deskripsi' => $pressRelease->deskripsi,
            'fotolink' => $fotolink->isNotEmpty() ?
                $fotolink['url'] :
                'https://magma.vsi.esdm.go.id/img/empty-esdm.jpg',
            'file' => $file->isNotEmpty() ?
                $file['url'] : null,
            'kodega' => $pressRelease->code,
            'press_pelapor' => request()->user()->name,
            'sent' => $pressRelease->is_published,
        ]);

        return $oldPressRelease;
    }

    /**
     * Update press release
     *
     * @param Request $request
     * @param PressRelease $pressRelease
     * @return PressRelease
     */
    public function updatePressRelease(Request $request, PressRelease $pressRelease): PressRelease
    {
        $pressRelease = tap($pressRelease)->update(
            $this->mergeAttributes($request)
        );

        $pressRelease->tags()->sync($request->tags);

        $this->clearCache();

        return $pressRelease;
    }
}