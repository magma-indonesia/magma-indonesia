<?php

namespace App\Http\Controllers;

use App\Gadd;
use App\Http\Requests\PressReleaseCreateRequest;
use App\PressRelease;
use App\Services\PressReleaseFileService;
use App\Services\PressReleaseService;
use App\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
            'pressReleases' => PressRelease::select('id', 'judul', 'slug', 'nip')
                ->withCount('press_release_files')->with('user')->get(),
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
            $pressRelease->press_release_files()
                ->createMany(
                    $pressReleaseFileService->storeFiles($request)->toArray()
                );

            return $pressRelease;
        });

        return $pressRelease->load('press_release_files');
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
        return view('press-release.edit', [
            'pressRelease' => $pressRelease->load('press_release_files', 'tags'),
            'tags' => Tag::all(),
            'categories' => $this->categories,
            'gadds' => Gadd::select('name', 'code')->orderBy('name')->get(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\PressRelease  $pressRelease
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, PressRelease $pressRelease)
    {
        //
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
