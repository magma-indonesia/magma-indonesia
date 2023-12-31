<?php

namespace App\Http\Controllers;

use App\Edukasi;
use App\Glossary;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class EdukasiController extends Controller
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
        Storage::disk('public')->put('edukasi/thumbnails/'.$filename, $thumbnail);

        return $filename;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('edukasi.index', ['edukasis' => Edukasi::withCount('edukasi_files')->get(), 'glossaries_count' => Glossary::count()]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('edukasi.create');
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
            'judul' => 'required|unique:edukasis|max:200',
            'deskripsi' => 'required|min:144',
            'is_published' => 'required|boolean',
            'files' => 'sometimes|required|array',
            'files.*' => 'sometimes|required|mimes:jpeg,jpg,png|max:5000',
        ]);

        $edukasi = new Edukasi();
        $edukasi->nip = auth()->user()->nip;
        $edukasi->judul = $request->judul;
        $edukasi->slug = $request->judul;
        $edukasi->deskripsi = $request->deskripsi;
        $edukasi->is_published = $request->is_published;
        $saved = $edukasi->save();

        $edukasi->refresh();

        if ($saved)
        {
            if ($request->has('files'))
            {
                foreach ($request->file('files') as $file) {
                    $edukasi->edukasi_files()->create([
                        'filename' => $this->createThumbnail(
                            $file->store('public/edukasi')
                        )
                    ]);
                }
            }

            return redirect()->route('chambers.edukasi.index')
                    ->with('message', 'Informasi baru berhasil ditambahkan');
        }

        return redirect()->route('chambers.edukasi.index')
            ->with('message', 'Informasi baru gagal ditambahkan');
    }

    /**
     * Display the specified resource.
     *
     * @param  String $slug
     * @return \Illuminate\Http\Response
     */
    public function show($slug)
    {
        return view('edukasi.show', ['edukasi' => Edukasi::with('edukasi_files')->whereSlug($slug)->firstOrFail()]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Edukasi  $edukasi
     * @return \Illuminate\Http\Response
     */
    public function edit(Edukasi $edukasi)
    {
        return view('edukasi.edit', ['edukasi' => $edukasi->load('edukasi_files')]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Edukasi  $edukasi
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Edukasi $edukasi)
    {
        if ($request->ajax())
        {
            $this->validate($request, [
                'is_published' => 'required|boolean',
            ]);

            $edukasi->is_published = $request->is_published;
            $edukasi->save();

            return response()->json([
                'status' => 200,
                'success' => 1,
                'message' => $edukasi->judul.' berhasil di-publish',
            ]);
        }

        $this->validate($request, [
            'judul' => 'required|max:200|unique:edukasis,id,'.$edukasi->id,
            'deskripsi' => 'required|min:144',
            'is_published' => 'required|boolean',
            'files' => 'sometimes|required|array',
            'files.*' => 'sometimes|required|mimes:jpeg,jpg,png|max:5000',
            'delete_files' => 'sometimes|required|array',
            'delete_files.*' => 'sometimes|required|exists:edukasi_files,id',
        ]);

        $edukasi->load('edukasi_files');

        $edukasi->nip = auth()->user()->nip;
        $edukasi->judul = $request->judul;
        $edukasi->slug = $request->judul;
        $edukasi->deskripsi = $request->deskripsi;
        $edukasi->is_published = $request->is_published;

        if ($request->has('delete_files'))
        {
            $edukasi->edukasi_files->whereIn('id',$request->delete_files)->each(function ($file) {
                Storage::disk('public')->delete('edukasi/thumbnails/'.$file->filename);
                Storage::disk('public')->delete('edukasi/'.$file->filename);
                $file->delete();
            });
        }

        if ($request->has('files')) {
            foreach ($request->file('files') as $file) {
                $edukasi->edukasi_files()->create([
                    'filename' => $this->createThumbnail(
                        $file->store('public/edukasi')
                    )
                ]);
            }
        }

        $edukasi->save();

        return redirect()->route('chambers.edukasi.index')
                ->with('message', 'Informasi berhasil dirubah');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Edukasi  $edukasi
     * @return \Illuminate\Http\Response
     */
    public function destroy(Edukasi $edukasi)
    {
        $edukasi->load('edukasi_files');

        foreach ($edukasi->edukasi_files as $file) {
            Storage::disk('public')->delete('edukasi/thumbnails/'.$file->filename);
            Storage::disk('public')->delete('edukasi/'.$file->filename);
        }

        if ($edukasi->delete()) {
            return [
                'message' => $edukasi->judul.' berhasil dihapus',
                'success' => true
            ];
        }

        return [
            'message' => $edukasi->judul.' gagal dihapus',
            'success' => false
        ];
    }
}
