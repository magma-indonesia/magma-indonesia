<?php

namespace App\Services;

use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class PressReleaseFileService
{
    public function createThumbnail(UploadedFile $file, string $type, string $name): void
    {
        $thumbnail = Image::make(storage_path("app/$name"))->resize(250, 250, function ($constraint) {
            $constraint->aspectRatio();
        })->encode('jpg');

        Storage::disk('public')->put("{$this->thumbnailPath($type)}/{$file->hashName()}", $thumbnail);
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
     * Undocumented function
     *
     * @param UploadedFile $file
     * @param string $type
     * @param string $name
     * @return array
     */
    public function toArray(UploadedFile $file, string $type, string $name): array
    {
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
     * Return all stored files
     *
     * @param UploadedFile $file
     * @param string $type
     * @return string
     */
    public function storeFile(UploadedFile $file, string $type): string
    {
        return $file->store("public/press-release/{$type}");
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
                    $name = $this->storeFile($file, $type);

                    if ($type !== 'files') {
                        $this->createThumbnail($file, $type, $name);
                    }

                    return $this->toArray($file, $type, $name);
                });
            }
        })->filter()->flatten(1)->values();
    }
}
