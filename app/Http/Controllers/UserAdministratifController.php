<?php

namespace App\Http\Controllers;

use App\UserAdministratif;
use Illuminate\Http\Request;

use App\User;

class UserAdministratifController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::orderBy('name')->get();
        return view('users.administratif.index')->with('users',$users);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\UserAdministratif  $userAdministratif
     * @return \Illuminate\Http\Response
     */
    public function show(UserAdministratif $userAdministratif)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\UserAdministratif  $userAdministratif
     * @return \Illuminate\Http\Response
     */
    public function edit(UserAdministratif $userAdministratif)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\UserAdministratif  $userAdministratif
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, UserAdministratif $userAdministratif)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\UserAdministratif  $userAdministratif
     * @return \Illuminate\Http\Response
     */
    public function destroy(UserAdministratif $userAdministratif)
    {
        //
    }
}
