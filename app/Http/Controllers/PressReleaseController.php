<?php

namespace App\Http\Controllers;

use App\Gadd;
use App\Http\Requests\PressReleaseCreateRequest;
use App\Http\Requests\PressReleaseUpdateRequest;
use App\PressRelease;
use App\Services\PressReleaseFileService;
use App\Services\PressReleaseService;
use App\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;

class PressReleaseController extends Controller
{
    protected $categories = [
        'gunung_api' => 'Gunung Api',
        'gerakan_tanah' => 'Gerakan Tanah',
        'gempa_bumi' => 'Gempa Bumi',
        'tsunami' => 'Tsunami',
        'lainnya' => 'Lainnya',
    ];

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('press-release.index', [
            'pressReleases' => PressRelease::select('id', 'judul', 'slug', 'nip', 'is_published')
                ->withCount('press_release_files')->with('user')->get(),
            'tagsCounts' => Tag::count(),
            'categories' => $this->categories,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('press-release.create', [
            'tags' => Tag::all(),
            'categories' => $this->categories,
            'gadds' => Gadd::select('name', 'code')->orderBy('name')->get(),
        ]);
    }

    /**
     * Store Press Release
     *
     * @param PressReleaseCreateRequest $request
     * @param PressReleaseService $pressReleaseService
     * @param PressReleaseFileService $pressReleaseFileService
     * @return \Illuminate\Http\Response
     */
    public function store(
        PressReleaseCreateRequest $request,
        PressReleaseService $pressReleaseService,
        PressReleaseFileService $pressReleaseFileService)
    {
        $pressRelease = DB::transaction(function () use ($request, $pressReleaseService, $pressReleaseFileService) {
            $pressRelease = $pressReleaseService->storePressRelease($request);
            $pressReleaseFileService->storeFiles($request, $pressRelease);

            return $pressRelease;
        });

        return redirect()
            ->route('chambers.press-release.index')
            ->with([
                'message' => "{$pressRelease->judul} berhasil dibuat",
                'url' => URL::route('press-release.show',
                    ['id' => $pressRelease->id, 'slug' => $pressRelease->slug]),
            ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\PressRelease  $pressRelease
     * @return \Illuminate\Http\Response
     */
    public function show(PressRelease $pressRelease)
    {
        return redirect()->route('press-release.show', [
            'id' => $pressRelease->id,
            'slug' => $pressRelease->slug
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\PressRelease  $pressRelease
     * @return \Illuminate\Http\Response
     */
    public function edit(PressRelease $pressRelease)
    {
        $pressRelease = $pressRelease->load('press_release_files', 'tags');

        return view('press-release.edit', [
            'pressRelease' => $pressRelease,
            'pressReleaseFiles' => $pressRelease->press_release_files->mapToGroups(function ($file) {
                return [$file->collection => $file];
            }),
            'tags' => Tag::all(),
            'categories' => $this->categories,
            'gadds' => Gadd::select('name', 'code')->orderBy('name')->get(),
        ]);
    }

    /**
     * Store update for press release
     *
     * @param PressReleaseUpdateRequest $request
     * @param PressRelease $pressRelease
     * @return \Illuminate\Http\Response
     */
    public function update(
        PressReleaseUpdateRequest $request,
        PressRelease $pressRelease,
        PressReleaseService $pressReleaseService,
        PressReleaseFileService $pressReleaseFileService
    ) {
        $pressRelease = $pressReleaseService->updatePressRelease($request, $pressRelease);

        $pressReleaseFileService->updateFiles($request, $pressRelease);

        return redirect()
            ->route('chambers.press-release.index')
            ->with([
                'message' => "{$pressRelease->judul} berhasil diperbarui",
                'url' => URL::route(
                    'press-release.show',
                    ['id' => $pressRelease->id, 'slug' => $pressRelease->slug]
                ),
            ]);
    }

    public function publish(Request $request)
    {
        return response()->json([
            'status' => 200,
            'success' => 1,
            'message' => "{$request->is_published} berhasil di-publish",
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\PressRelease  $pressRelease
     * @return \Illuminate\Http\Response
     */
    public function destroy(PressRelease $pressRelease)
    {
        //
    }
}
