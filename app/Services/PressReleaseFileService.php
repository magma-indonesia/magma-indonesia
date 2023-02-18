<?php

namespace App\Services;

use App\PressRelease;
use App\PressReleaseFile;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class PressReleaseFileService
{
    /**
     * Create thumbnail
     *
     * @param UploadedFile $file
     * @param string $type
     * @param string $name
     * @return void
     */
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
    public function toArray(UploadedFile $file, string $type, string $name, string $overview = null, string $disk = 'public'): array
    {
        return [
            'name' => $file->hashName(),
            'file_name' => $file->getClientOriginalName(),
            'mime_type' => $file->getClientMimeType(),
            'path' => $this->path($type),
            'disk' => $disk,
            'file_hash' => hash_file('sha256', storage_path("app/$name")),
            'collection' => $type,
            'overview' => $overview,
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
    public function store(Request $request): Collection
    {
        return collect(['files', 'petas', 'gambars'])->transform(function ($type) use ($request) {
            if ($request->has($type)) {
                return collect($request->file($type))->transform(function ($file, $index) use ($type, $request) {
                    $name = $this->storeFile($file, $type);

                    if ($type !== 'files') {
                        $this->createThumbnail($file, $type, $name);
                    }

                    $overview = $request->overviews[$type][$index];

                    return $this->toArray($file, $type, $name, $overview);
                });
            }
        })->filter()->flatten(1)->values();
    }

    /**
     * Destroy files
     *
     * @param EloquentCollection $pressReleaseFiles
     * @return void
     */
    public function destroyFiles(EloquentCollection $pressReleaseFiles): void
    {
        $pressReleaseFiles->whenNotEmpty(function ($files) {
            $files->each(function ($file) {

                if ($file->collection === 'petas' || $file->collection === 'gambars') {
                    Storage::disk($file->disk)->delete("$file->path/thumbnails/$file->name");
                }

                Storage::disk($file->disk)->delete("$file->path/$file->name");
                $file->delete();
            });
        });
    }

    /**
     * Save files
     *
     * @param Request $request
     * @param PressRelease $pressRelease
     * @return PressRelease
     */
    public function storeFiles(Request $request, PressRelease $pressRelease): PressRelease
    {
        return $this->save($request, $pressRelease);
    }

    /**
     * Update files
     *
     * @param Request $request
     * @param PressRelease $pressRelease
     * @return PressRelease
     */
    public function updateFiles(Request $request, PressRelease $pressRelease): PressRelease
    {
        if ($request->has('overview_updates')) {
            $this->updateOverviews($request->overview_updates);
        }

        if ($request->has('delete_files')) {
            $this->destroyFiles(
                PressReleaseFile::whereIn('id', $request->delete_files)->get()
            );
        }

        return $this->save($request, $pressRelease);
    }

    /**
     * Update overview column
     *
     * @param array $overviews
     * @return void
     */
    public function updateOverviews(array $overviews): void
    {
        collect($overviews)->each(function ($overview, $id) {
            $pressReleaseFile = PressReleaseFile::findOrFail($id);
            $pressReleaseFile->overview = $overview;
            $pressReleaseFile->save();
        });
    }

    /**
     * Save file
     *
     * @param Request $request
     * @param PressRelease $pressRelease
     * @return PressRelease
     */
    public function save(Request $request, PressRelease $pressRelease): PressRelease
    {
        $pressRelease->press_release_files()
            ->createMany(
                $this->store($request)->toArray()
            );

        return $pressRelease->refresh();
    }
}
