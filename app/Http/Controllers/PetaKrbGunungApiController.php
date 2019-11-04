<?php

namespace App\Http\Controllers;

use App\Gadd;
use Illuminate\Http\Request;
use App\PetaKrbGunungApi as KRB;
use Illuminate\Support\Facades\Storage;

class PetaKrbGunungApiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $krbs = KRB::with('gunungapi:code,name')->get();
        return view('gunungapi.krb.index', compact('krbs'));
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

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        ini_set('max_execution_time', 1200);

        $this->validate($request, [
            'code' => 'required|exists:ga_dd,code',
            'krb' => 'required|max:80000|mimes:jpeg,jpg',
        ],[
            'code.required' => 'Gunung Api belum dipilih',
            'code.exists' => 'Gunung Api tidak ditemukan',
            'krb.required' => 'File KRB tidak ditemukan',
            'krb.max' => 'File KRB harus kurang dari <80MB',
            'krb.mimes' => 'File KRB harus berformat JPG',
        ]);

        KRB::create([
            'code' => $request->code,
            'filename' => $request->file('krb')->store(
                '/', 'krb-gunungapi'
            ),
            'nip' => auth()->user()->nip,
            'keterangan' => $request->keterangan,
            'published' => $request->published,
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
        //
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
