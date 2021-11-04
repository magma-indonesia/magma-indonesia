<?php

namespace App\Http\Controllers;

use App\Edukasi;
use App\Glossary;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class GlossaryController extends Controller
{
    /**
     * Undocumented function
     *
     * @param String $path
     * @return void
     */
    protected function createThumbnail(String $path)
    {
        $filename = last(explode('/',$path));
        $thumbnail = Image::make(storage_path('app/'.$path))->resize(150, 150, function ($constraint) {
            $constraint->aspectRatio();
        })->encode('jpg');
        Storage::disk('public')->put('glossary/thumbnails/'.$filename, $thumbnail);

        return $filename;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('glossary.index', [
            'glossaries' => Glossary::orderBy('judul')->with('user:nip,name')->withCount('glossary_files')->get(),
            'edukasis_count' => Edukasi::count(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('glossary.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'judul' => 'required|unique:glossaries|max:200',
            'deskripsi' => 'required|min:20',
            'referensi' => 'nullable',
            'is_published' => 'required|boolean',
            'files' => 'sometimes|required|array',
            'files.*' => 'sometimes|required|image|max:3000',
        ]);

        $glossary = new Glossary();
        $glossary->nip = auth()->user()->nip;
        $glossary->judul = $request->judul;
        $glossary->slug = $request->judul;
        $glossary->deskripsi = $request->deskripsi;
        $glossary->reference = $request->referensi;
        $glossary->is_published = $request->is_published;
        $saved = $glossary->save();

        $glossary->refresh();

        if ($saved)
        {
            if ($request->has('files'))
            {
                foreach ($request->file('files') as $file) {
                    $glossary->glossary_files()->create([
                        'filename' => $this->createThumbnail(
                            $file->store('public/glossary')
                        )
                    ]);
                }
            }

            return redirect()->route('chambers.glossary.index')
                    ->with('message', 'Glossary berhasil ditambahkan');
        }

        return redirect()->route('chambers.glossary.index')
            ->with('message', 'Glossary gagal ditambahkan');
    }

    /**
     * Display the specified resource.
     *
     * @param  String $slug
     * @return \Illuminate\Http\Response
     */
    public function show(String $slug)
    {
        $glossary = Glossary::whereSlug($slug)->firstOrFail();
        return view('glossary.show',['glossary' => $glossary]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Glossary  $glossary
     * @return \Illuminate\Http\Response
     */
    public function edit(Glossary $glossary)
    {
        return view('glossary.edit', ['glossary' => $glossary->load('glossary_files')]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Glossary  $glossary
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Glossary $glossary)
    {
        if ($request->ajax())
        {
            $this->validate($request, [
                'is_published' => 'required|boolean',
            ]);

            $glossary->is_published = $request->is_published;
            $glossary->save();

            return response()->json([
                'status' => 200,
                'success' => 1,
                'message' => $glossary->judul.' berhasil di-publish',
            ]);
        }

        $this->validate($request, [
            'judul' => 'required|max:200|unique:glossaries,id,'.$glossary->id,
            'deskripsi' => 'required|min:100',
            'referensi' => 'nullable',
            'is_published' => 'required|boolean',
            'files' => 'sometimes|required|array',
            'files.*' => 'sometimes|required|image|max:3000',
            'delete_files' => 'sometimes|required|array',
            'delete_files.*' => 'sometimes|required|exists:glossary_files,id',
        ]);

        $glossary->load('glossary_files');

        $glossary->nip = auth()->user()->nip;
        $glossary->judul = $request->judul;
        $glossary->slug = $request->judul;
        $glossary->reference = $request->referensi;
        $glossary->deskripsi = $request->deskripsi;
        $glossary->is_published = $request->is_published;

        if ($request->has('delete_files'))
        {
            $glossary->glossary_files->whereIn('id',$request->delete_files)->each(function ($file) {
                Storage::disk('public')->delete('glossary/thumbnails/'.$file->filename);
                Storage::disk('public')->delete('glossary/'.$file->filename);
                $file->delete();
            });
        }

        if ($request->has('files')) {
            foreach ($request->file('files') as $file) {
                $glossary->glossary_files()->create([
                    'filename' => $this->createThumbnail(
                        $file->store('public/glossary')
                    )
                ]);
            }
        }

        $glossary->save();

        return redirect()->route('chambers.glossary.index')
                ->with('message', 'Glossary berhasil dirubah');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Glossary  $glossary
     * @return \Illuminate\Http\Response
     */
    public function destroy(Glossary $glossary)
    {
        $glossary->load('glossary_files');

        foreach ($glossary->glossary_files as $file) {
            Storage::disk('public')->delete('glossary/thumbnails/'.$file->filename);
            Storage::disk('public')->delete('glossary/'.$file->filename);
        }

        if ($glossary->delete()) {
            return [
                'message' => $glossary->judul.' berhasil dihapus',
                'success' => true
            ];
        }

        return [
            'message' => $glossary->judul.' gagal dihapus',
            'success' => false
        ];
    }
}
