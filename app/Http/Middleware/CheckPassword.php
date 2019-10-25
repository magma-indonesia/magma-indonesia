<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Hash;

class CheckPassword
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (session('password_changed',0))
        {
            return $next($request);
        }

        if (Hash::check('esdm1234', auth()->user()->password))
        {
            return redirect()->route('change-password.index');
        }

        session(['password_changed' => 1]);
        return $next($request);
    }
}
