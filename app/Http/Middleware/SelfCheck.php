<?php

namespace App\Http\Middleware;

use Closure;
use App\User;

class SelfCheck
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
        if (request()->user()->hasRole('Super Admin'))
            return $next($request);

        $user = User::select('nip')
                ->where('id',$request->route('user'))
                ->first();

        if (auth()->user()->nip == $user->nip)
            return $next($request);

        return redirect()->route('chambers.users.index');
    }
}
