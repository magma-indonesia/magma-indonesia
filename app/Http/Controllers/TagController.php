<?php

namespace App\Http\Controllers;

use App\Http\Requests\TagCreateRequest;
use App\Http\Requests\TagUpdateRequest;
use App\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

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
            'tags' => Tag::all(),
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
        $tag = Tag::create(['name' => Str::title($request->name)]);

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
        return redirect()->route('chambers.tag.index');
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

        $tag->update(['name' => Str::title($request->name)]);
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
