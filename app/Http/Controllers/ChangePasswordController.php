<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;

class ChangePasswordController extends Controller
{
    public function index()
    {
        return view('users.change-password');
    }

    public function update(Request $request)
    {
        $this->validate($request, [
            'password' => 'required|confirmed|min:6|not_in:esdm1234'
        ],[
            'password.required' => 'Password tidak boleh kosong',
            'password.confirmed' => 'Konfirmasi Password tidak sama',
            'password.min' => 'Panjang Password minimal 6 karakter',
            'password.not_in' => 'Password tidak boleh menggunakan default',
        ]);

        auth()->user()->update(['password' => $request->password]);

        return redirect()->route('chambers.index');
    }
}
