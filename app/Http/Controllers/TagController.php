<?php

namespace App\Http\Controllers;

use App\Http\Requests\TagCreateRequest;
use App\Http\Requests\TagUpdateRequest;
use App\PressRelease;
use App\Tag;

class TagController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('tag.index', [
            'press_release_count' => PressRelease::count(),
            'tags' => Tag::withCount('press_releases')->get(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return redirect()->route('chambers.tag.index');
    }


    /**
     * Undocumented function
     *
     * @param TagCreateRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(TagCreateRequest $request)
    {
        $tag = Tag::create(['name' => $request->name]);

        return redirect()->route('chambers.tag.index')
            ->with('message', "$tag->name berhasil ditambahkan");
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Tag  $tag
     * @return \Illuminate\Http\Response
     */
    public function show(Tag $tag)
    {
        return $tag->load([
            'press_releases:press_releases.id,judul,slug,short_deskripsi',
            'press_releases.press_release_files',
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Tag  $tag
     * @return \Illuminate\Http\Response
     */
    public function edit(Tag $tag)
    {
        return view('tag.edit', [
            'tag' => $tag,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  TagCreateRequest $request
     * @param  \App\Tag  $tag
     * @return \Illuminate\Http\Response
     */
    public function update(TagUpdateRequest $request, Tag $tag)
    {
        $oldName = $tag->name;

        $tag->update(['name' => $request->name]);
        $tag->save();

        return redirect()->route('chambers.tag.index')
        ->with('message', "$oldName berhasil dirubah menjadi $tag->name");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Tag  $tag
     * @return \Illuminate\Http\Response
     */
    public function destroy(Tag $tag)
    {
        if ($tag->delete()) {
            return [
                'message' => $tag->judul . ' berhasil dihapus',
                'success' => true
            ];
        }

        return [
            'message' => $tag->judul . ' gagal dihapus',
            'success' => false
        ];
    }
}
