<?php

namespace App\Http\Controllers;

use App\UserAdministratif;
use App\User;
use App\UserBidang as Bidang;
use App\Jabatan;
use App\Fungsional;
use App\Kantor;
use App\Golongan;
use Illuminate\Http\Request;

class UserAdministratifController extends Controller
{

    /**
     * Adding middleware for protecttion
     * 
     * @return boolean
     */
    public function __construct()
    {
        $this->middleware('owner')->only(['edit','update']);
        $this->middleware('role:Super Admin')->only(['destroy']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::with('administrasi.bidang','administrasi.kantor')
                    ->orderBy('name')
                    ->get();
                    
        return view('users.administrasi.index',compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        return redirect()->route('chambers.administratif.administrasi.edit',['id' => $request->id]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store()
    {
        return redirect()->route('chambers.administratif.administrasi.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\UserAdministratif  $userAdministratif
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        return redirect()->route('chambers.administratif.administrasi.index');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\UserAdministratif  $userAdministratif
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $userAdministratif = UserAdministratif::with('user','bidang','jabatan','kantor')
                                ->findOrFail($id);

        $bidangs = Bidang::whereNotIn('code',['pvg'])->orderBy('nama')->get();
        $jabatans = Jabatan::orderBy('nama')->get();
        $fungsionals = Fungsional::orderBy('nama')->get();
        $kantors = Kantor::all();
        $golongans = Golongan::all();

        return view('users.administrasi.edit',compact('userAdministratif','bidangs','jabatans','fungsionals','kantors','golongans'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\UserAdministratif  $userAdministratif
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

        $userAdministratif = UserAdministratif::findOrFail($id);

        $this->validate($request,[
            'bidang' => 'required|exists:user_bidangs,id',
            'kantor' => 'required|exists:kantors,code',
            'jabatan' => 'required|exists:jabatans,id',
            'fungsional' => 'required|exists:fungsionals,id',
            'golongan' => 'required|exists:golongans,id',
        ],[
            'bidang.exists' => 'Bidang tidak terdaftar',
            'kantor.exists' => 'Kantor tidak terdaftar',
            'jabatan.exists' => 'Jabatan tidak terdaftar',
            'fungsional.exists' => 'Data Fungsional tidak terdaftar',
            'golongan.exists' => 'Golongan/Pangkat tidak terdaftar',
        ]);

        $userAdministratif->user_id = auth()->user()->id;
        $userAdministratif->bidang_id = $request->bidang;
        $userAdministratif->kantor_id = $request->kantor;
        $userAdministratif->jabatan_id = $request->jabatan;
        $userAdministratif->fungsional_id = $request->fungsional;
        $userAdministratif->golongan_id = $request->golongan;
        $userAdministratif->save();

        return redirect()->route('chambers.administratif.administrasi.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\UserAdministratif  $userAdministratif
     * @return \Illuminate\Http\Response
     */
    public function destroy()
    {
        return redirect()->route('chambers.administratif.administrasi.index');
    }
}
