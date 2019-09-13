<?php

namespace App\Http\Controllers\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use App\v1\PressRelease as Press;
use App\User;
use Carbon\Carbon;

class PressReleaseController extends Controller
{
    /**
     * Adding middleware for protecttion
     * 
     * @return boolean
     */
    public function __construct()
    {
        $this->middleware('role:Super Admin|Humas PVMBG|Struktural')
                ->except(['index','show']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $presses = Press::orderBy('id','desc')->paginate(15);
        return view('v1.press.index', compact('presses'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $users = User::orderBy('name')->get();
        return view('v1.press.create',compact('users'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request,[
            'nama'      => 'required',
            'judul'     => 'required|min:10|max:200',
            'deskripsi' => 'required|min:140',
            'file'      => 'nullable|image|mimes:jpg,png,jpeg,gif|max:700'
        ]);

        $press = new Press();
        $press->datetime = Carbon::now()->toDateTimeString();
        $press->press_pelapor = $request->nama;
        $press->judul = $request->judul;
        $press->deskripsi = $request->deskripsi;
        $press->sent = 1;

        if ($request->hasFile('file')) 
        {
            $filename = 'v2_'.time().'.'.$request->file->getClientOriginalExtension();
            $upload = $request->file('file')
                        ->storeAs(
                            'img/pr',
                            $filename,
                            'magma-old-ftp'
                        );
            $press->fotolink = 'https://magma.vsi.esdm.go.id/'.$upload;   
        } else {
            $press->fotolink = 'https://magma.vsi.esdm.go.id/img/empty-esdm.jpg';
        }

        $messages = $press->save() ? 
                        'Press Release : '. $request->judul.' telah ditambahkan!' :
                        'Press Release : '. $request->judul.' gagal ditambahkan!' ;

        return redirect()->route('chambers.v1.press.index')
                    ->with('flash_message',$messages);
            
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $press = Press::findOrFail($id);
        return view('v1.press.show',compact('press'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $press = Press::findOrFail($id);
        $users = User::orderBy('name')->get();
        return view('v1.press.edit',compact('press','users'));
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
        $this->validate($request,[
            'nama'      => 'required',
            'judul'     => 'required|min:10|max:200',
            'deskripsi' => 'required|min:140',
            'file'      => 'nullable|image|mimes:jpg,png,jpeg,gif|max:700'
        ]);

        $press =  Press::findOrFail($id);
        $press->press_pelapor = $request->nama;
        $press->press_editor = $request->nama;
        $press->judul = $request->judul;
        $press->deskripsi = $request->deskripsi;
        $press->sent = 1;

        if ($request->hasFile('file')) 
        {
            $filename = 'v2_'.time().'.'.$request->file->getClientOriginalExtension();
            $upload = $request->file('file')
                        ->storeAs(
                            'img/pr',
                            $filename,
                            'magma-old-ftp'
                        );
            $press->fotolink = 'https://magma.vsi.esdm.go.id/'.$upload;   
        }

        $messages = $press->save() ? 
                        'Press Release : '. $request->judul.' berhasil dirubah!' :
                        'Press Release : '. $request->judul.' gagal dirubah!' ;

        return redirect()->route('chambers.v1.press.index')
                    ->with('flash_message',$messages);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $press  = Press::findOrFail($id);
        $judul  = $press->judul;

        try {
            $press->delete();

            $data = [
                'success' => 1,
                'message' => $judul.' berhasil dihapus.'
            ];
    
            return response()->json($data);
        }

        catch (Exception $e) {
            return $e;
        }

    }
}
