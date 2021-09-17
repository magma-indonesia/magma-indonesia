<?php

namespace App\Http\Controllers;

use App\Gadd;
use App\HomeKrb;
use App\Http\Requests\PetaKrbGunungApiRequest;
use Illuminate\Http\Request;
use App\PetaKrbGunungApi as KRB;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class PetaKrbGunungApiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('gunungapi.krb.index', [
            'krbs' => KRB::with('gunungapi:code,name')->get(),
            'home_krbs' => HomeKrb::orderBy('created_at','desc')->get()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $gadds = Gadd::select('code','name')->orderBy('name')->get();
        return view('gunungapi.krb.create', compact('gadds'));
    }

    protected function createFile(String $path)
    {
        $filename = last(explode('/',$path));

        $large = Image::make(storage_path('app/'.$path))->resize(2560, 2560, function ($constraint) {
            $constraint->aspectRatio();
        })->encode('jpg');

        Storage::disk('public')->put('krb-gunungapi/large/'.$filename, $large);

        $medium = Image::make(storage_path('app/public/krb-gunungapi/large/'.$filename))->resize(1280, 1280, function ($constraint) {
            $constraint->aspectRatio();
        })->encode('jpg');

        $medium = Storage::disk('public')->put('krb-gunungapi/medium/'.$filename, $medium);

        $thumbnail = Image::make(storage_path('app/public/krb-gunungapi/medium/'.$filename))->resize(150, 150, function ($constraint) {
            $constraint->aspectRatio();
        })->encode('jpg');

        Storage::disk('public')->put('krb-gunungapi/thumbnails/'.$filename, $thumbnail);

        return [
            'filename' => $filename,
            'size' => Storage::disk('krb-gunungapi')->size($filename),
            'large_size' => Storage::disk('krb-gunungapi')->size('large/'.$filename),
            'medium_size' => Storage::disk('krb-gunungapi')->size('medium/'.$filename),
        ];
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\PetaKrbGunungApiRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PetaKrbGunungApiRequest $request)
    {
        ini_set('max_execution_time', 1200);

        $validated = $request->validated();

        return $validated;

        $meta = $this->createFile(
            $request->file('krb')->store('public/krb-gunungapi')
        );

        KRB::create([
            'code' => $validated['code'],
            'tahun' => $validated['tahun'],
            'filename' => $meta['filename'],
            'size' => $meta['size'],
            'large_size' => $meta['large_size'],
            'medium_size' => $meta['medium_size'],
            'nip' => auth()->user()->nip,
            'keterangan' => $validated['keterangan'],
            'published' => $validated['published'],
        ]);

        return redirect()->route('chambers.krb-gunungapi.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $krb = KRB::findOrFail($id);
        return Storage::disk('krb-gunungapi')->download($krb->filename);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if ($request->ajax())
        {
            $krb = KRB::findOrFail($id);
            $this->validate($request, [
                'published' => 'required|boolean',
            ]);

            $krb->published = $request->published;
            $krb->save();

            return response()->json([
                'status' => 200,
                'success' => 1,
                'message' => 'Peta KRB berhasil di-update',
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $krb = KRB::findOrFail($id);
        $filename = $krb->filename;

        if ($krb->delete()) {
            Storage::disk('krb-gunungapi')->delete($filename);
            $data = [
                'success' => 1,
                'message' => 'Data berhasil dihapus.'
            ];

            return response()->json($data);
        }

        $data = [
            'success' => 0,
            'message' => 'Gagal dihapus.'
        ];

        return response()->json($data);
    }
}
