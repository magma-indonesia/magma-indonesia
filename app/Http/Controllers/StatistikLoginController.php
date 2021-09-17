<?php

namespace App\Http\Controllers;

use App\StatistikLogin;
use Illuminate\Http\Request;

class StatistikLoginController extends Controller
{
    public function index($date = null)
    {
        $logins = StatistikLogin::query();
        $logins = filled($date) ? $logins->where('date', $date) : $logins;
        $logins = $logins->with('user')->orderBy('date', 'desc');

        return view('users.statistik.login', [
            'logins' => $logins->paginate(30),
            'names' => StatistikLogin::with('user')->select('nip')->distinct()->get(),
        ]);
    }
}
