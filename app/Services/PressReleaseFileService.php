<?php

namespace App\Services;

use App\PressRelease;
use App\PressReleaseFile;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;

class PressReleaseFileService
{
    public function createThumbnail()
    {

    }

    /**
     * Get path
     *
     * @param string $type
     * @return string
     */
    public function path(string $type): string
    {
        return "press-release/{$type}";
    }

    /**
     * Get thumbnail path
     *
     * @param string $type
     * @return string
     */
    public function thumbnailPath(string $type): string
    {
        return "press-release/{$type}/thumbnails";
    }

    /**
     * Return all stored files
     *
     * @param UploadedFile $file
     * @param string $type
     * @return array
     */
    public function storeFile(UploadedFile $file, string $type): array
    {
        $name = $file->store("public/press-release/{$type}");

        return [
            'name' => $file->hashName(),
            'file_name' => $file->getClientOriginalName(),
            'mime_type' => $file->getClientMimeType(),
            'path' => $this->path($type),
            'disk' => 'public',
            'file_hash' => hash_file('sha256', storage_path("app/$name")),
            'collection' => $type,
            'size' => $file->getSize(),
        ];
    }

    /**
     * Store files
     *
     * @param Request $request
     * @return Collection
     */
    public function storeFiles(Request $request): Collection
    {
        return collect(['files', 'petas', 'gambars'])->transform(function ($type) use ($request) {
            if ($request->has($type)) {
                return collect($request->file($type))->transform(function ($file) use ($type) {
                    return $this->storeFile($file, $type);
                });
            }
        })->filter()->flatten(1)->values();
    }
}
