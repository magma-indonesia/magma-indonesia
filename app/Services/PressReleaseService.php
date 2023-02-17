<?php

namespace App\Services;

use App\PressRelease;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class PressReleaseService
{
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