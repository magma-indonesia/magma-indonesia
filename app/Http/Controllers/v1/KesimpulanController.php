<?php

namespace App\Http\Controllers\v1;

use App\v1\Kesimpulan;
use App\v1\Gadd;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class KesimpulanController extends Controller
{
    /**
     * Adding middleware for protecttion
     * 
     * @return boolean
     */
    public function __construct()
    {
        $this->middleware('role:Super Admin')->only(['destroy']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $kesimpulans = Kesimpulan::with(
            'gunungapi:ga_code,ga_nama_gapi',
            'user:vg_nip,vg_nama')
            ->withCount('vars')
            ->get();

        return view('v1.gunungapi.form-kesimpulan.index', compact('kesimpulans'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $gadds = Gadd::select('ga_code','ga_nama_gapi')->orderBy('ga_nama_gapi')->get();
        return view('v1.gunungapi.form-kesimpulan.create', compact('gadds'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validated = $this->validate($request, 
            [
                'ga_code' => 'required|exists:magma.ga_dd,ga_code',
                'level' =>'required|in:1,2,3,4',
                'kesimpulan' => 'required|array',
                'kesimpulan.0' => 'required',
                'kesimpulan.1' => 'nullable',
                'kesimpulan.2' => 'nullable',
                'kesimpulan.3' => 'nullable',
                'kesimpulan.4' => 'nullable',
            ],[
                'ga_code.required' => 'Gunung Api harus dipilih',
                'ga_code.exists' => 'Gunung Api tidak ada dalam database',
                'kesimpulan.array' => 'Format Kesimpulan harus dalam array',
                'kesimpulan.required' => 'Kesimpulan belum diisi',
                'kesimpulan.0.required' => 'Kesimpulan Pertama harus diisi',
                'kesimpulan.*.nullable' => 'Kesimpulan Kedua hingga Kelima bisa dikosongi',
            ]);

        $kesimpulan = new Kesimpulan;
        $kesimpulan->ga_code = $request->ga_code;
        $kesimpulan->level = $request->level;
        $kesimpulan->kesimpulan_1 = $request->kesimpulan[0];
        $kesimpulan->kesimpulan_2 = $request->kesimpulan[1];
        $kesimpulan->kesimpulan_3 = $request->kesimpulan[2];
        $kesimpulan->kesimpulan_4 = $request->kesimpulan[3];
        $kesimpulan->kesimpulan_5 = $request->kesimpulan[4];
        $kesimpulan->nip = auth()->user()->nip;
        $kesimpulan->save();

        return redirect()->route('chambers.v1.gunungapi.form-kesimpulan.index')->with('flash_message','Kesimpulan berhasil ditambahkan!');       
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\v1\Kesimpulan  $kesimpulan
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        return redirect()->route('chambers.v1.gunungapi.form-kesimpulan.index');        
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\v1\Kesimpulan  $kesimpulan
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $kesimpulan = Kesimpulan::withCount('vars')->findOrFail($id);

        if (auth()->user()->nip != $kesimpulan->nip AND !auth()->user()->hasRole('Super Admin'))
            return redirect()->route('chambers.v1.gunungapi.form-kesimpulan.index');

        $gadds = Gadd::select('ga_code','ga_nama_gapi')->orderBy('ga_nama_gapi')->get();
        return view('v1.gunungapi.form-kesimpulan.edit', compact('gadds','kesimpulan'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\v1\Kesimpulan  $kesimpulan
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $kesimpulan = Kesimpulan::withCount('vars')->findOrFail($id);

        $validated = $this->validate($request, 
            [
                'ga_code' => 'sometimes|required|exists:magma.ga_dd,ga_code',
                'level' =>'required|in:1,2,3,4',
                'kesimpulan' => 'required|array',
                'kesimpulan.0' => 'required',
                'kesimpulan.1' => 'nullable',
                'kesimpulan.2' => 'nullable',
                'kesimpulan.3' => 'nullable',
                'kesimpulan.4' => 'nullable',
            ],[
                'ga_code.sometimes' => 'Gunung Api harus dipilih',
                'ga_code.required' => 'Gunung Api harus dipilih',
                'ga_code.exists' => 'Gunung Api tidak ada dalam database',
                'kesimpulan.array' => 'Format Kesimpulan harus dalam array',
                'kesimpulan.required' => 'Kesimpulan belum diisi',
                'kesimpulan.0.required' => 'Kesimpulan Pertama harus diisi',
                'kesimpulan.*.nullable' => 'Kesimpulan Kedua hingga Kelima bisa dikosongi',
            ]);

        $kesimpulan->ga_code = $request->ga_code;
        $kesimpulan->level = $request->level;
        $kesimpulan->kesimpulan_1 = $request->kesimpulan[0];
        $kesimpulan->kesimpulan_2 = $request->kesimpulan[1];
        $kesimpulan->kesimpulan_3 = $request->kesimpulan[2];
        $kesimpulan->kesimpulan_4 = $request->kesimpulan[3];
        $kesimpulan->kesimpulan_5 = $request->kesimpulan[4];
        $kesimpulan->nip = auth()->user()->nip;
        $kesimpulan->save();

        return redirect()->route('chambers.v1.gunungapi.form-kesimpulan.index')->with('flash_message','Kesimpulan berhasil dirubah!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\v1\Kesimpulan  $kesimpulan
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $kesimpulan = Kesimpulan::findOrFail($id);

        if ($kesimpulan->delete()) {
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
