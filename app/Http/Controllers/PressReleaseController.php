<?php

namespace App\Http\Controllers;

use App\Gadd;
use App\Http\Requests\PressReleaseCreateRequest;
use App\PressRelease;
use App\Services\PressReleaseFileService;
use App\Services\PressReleaseService;
use App\Tag;
use Illuminate\Http\Request;

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
        return PressRelease::all();
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
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(
        PressReleaseCreateRequest $request,
        PressReleaseService $pressReleaseService,
        PressReleaseFileService $pressReleaseFileService)
    {
        return $request;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\PressRelease  $pressRelease
     * @return \Illuminate\Http\Response
     */
    public function show(PressRelease $pressRelease)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\PressRelease  $pressRelease
     * @return \Illuminate\Http\Response
     */
    public function edit(PressRelease $pressRelease)
    {
        //
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
