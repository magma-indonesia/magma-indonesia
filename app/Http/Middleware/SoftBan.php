<?php

namespace App\Http\Middleware;

use Closure;

class SoftBan
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
        if ($request->user()->nip == '196511071994032001') {
            abort(404);
        }

        return $next($request);
    }
}
