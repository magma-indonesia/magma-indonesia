<?php

namespace App\Http\Controllers;

use App\Stakeholder;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\StakeholderRequest;

class StakeholderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $stakeholders = Stakeholder::simplePaginate();
        return view('stakeholder.index', compact('stakeholders'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('stakeholder.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StakeholderRequest $request)
    {
        $stakeholder = new Stakeholder;
        $stakeholder->app_name = $request->app_name;
        $stakeholder->uuid = Str::uuid();
        $stakeholder->organisasi = $request->instansi;
        $stakeholder->api_type = $request->type;
        $stakeholder->secret_key = Hash::make(config('jwt.secret'));
        $stakeholder->kontak_nama = $request->nama;
        $stakeholder->kontak_phone = $request->phone;
        $stakeholder->kontak_email = $request->email;
        $stakeholder->status = $request->status;
        $stakeholder->expired_at = $request->date;
        $stakeholder->nip = auth()->user()->nip;
        $stakeholder->save();

        return $stakeholder;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Stakeholder  $stakeholder
     * @return \Illuminate\Http\Response
     */
    public function show(Stakeholder $stakeholder)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Stakeholder  $stakeholder
     * @return \Illuminate\Http\Response
     */
    public function edit(Stakeholder $stakeholder)
    {
        return view('stakeholder.edit', compact('stakeholder'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Stakeholder  $stakeholder
     * @return \Illuminate\Http\Response
     */
    public function update(StakeholderRequest $request, Stakeholder $stakeholder)
    {
        $stakeholder->app_name = $request->app_name;
        $stakeholder->organisasi = $request->instansi;
        $stakeholder->api_type = $request->type;
        $stakeholder->kontak_nama = $request->nama;
        $stakeholder->kontak_phone = $request->phone;
        $stakeholder->kontak_email = $request->email;
        $stakeholder->status = $request->status;
        $stakeholder->expired_at = $request->date;
        $stakeholder->nip = auth()->user()->nip;
        try {
            $stakeholder->save();

            return redirect()->route('chambers.stakeholder.index')
            ->with('flash_message',
            $stakeholder->app_name.' berhasil dirubah.');
        }
        catch (Exception $e) {
            return redirect()->route('chambers.stakeholder.index')
            ->with('flash_message',
            $request->app_name.' Gagal dirubah.');
        }
    
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Stakeholder  $stakeholder
     * @return \Illuminate\Http\Response
     */
    public function destroy(Stakeholder $stakeholder)
    {
        try {

            $stakeholder->delete();
            return response()->json([
                'success' => 1,
                'message' => $stakeholder->app_name.' berhasil dihapus.'
            ]);

        }

        catch (Exception $e) {
            return response()->json([
                'success' => 0,
                'message' => $stakeholder->app_name.' gagal dihapus.'
            ], 500);
        }
    }

}
