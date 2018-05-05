<?php

namespace App\Http\Controllers;

use App\Jabatan;
use Illuminate\Http\Request;

use Spatie\Permission\Models\Role;

class JabatanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $jabatans = Jabatan::all();

        return view('administratif.jabatan.index',compact('jabatans'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $jabatans = Jabatan::all();
        
        return view('administratif.jabatan.create',compact('jabatans'));
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
            'name.*' => 'required|max:100|unique:jabatans,nama',
            'roles' => 'required|boolean'
        ]);

        $names = $request->name;

        foreach ($names as $name) {
            $jabatan = new Jabatan();
            $jabatan->nama = $name;

            if ($request->roles ==  1)
            {
                $role = new Role();
                $role->name = $jabatan->nama;
                $role->save();
            }

            $jabatan->save();
        }

        return redirect()->route('administratif.jabatan.index')
            ->with('flash_message',
             'Jabatan <b>'. implode($request->name,', ').'</b> berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Jabatan  $jabatan
     * @return \Illuminate\Http\Response
     */
    public function show(Jabatan $jabatan)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Jabatan  $jabatan
     * @return \Illuminate\Http\Response
     */
    public function edit(Jabatan $jabatan)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Jabatan  $jabatan
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name'=>'required|max:100|unique:jabatans,nama'
        ]);

        $jabatan = Jabatan::findOrFail($id);
        $name = $jabatan->nama;
        $jabatan->nama = $request->name;
        $jabatan->save();

        $role = Role::where('name','=',$name)->first();
        if(!empty($role))
        {
            $role->name = $request->name;
            $role->save();
        }

        $data = [
            'success' => 1,
            'message' => 'Jabatan '.$request->name.' berhasil dirubah.'
        ];

        return response()->json($data);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Jabatan  $jabatan
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $jabatan = Jabatan::findOrFail($id);
        $name = $jabatan->nama;
        $jabatan->delete();

        $data = [
            'success' => 1,
            'message' => $name.' berhasil dihapus.'
        ];

        return response()->json($data);
    }
}
