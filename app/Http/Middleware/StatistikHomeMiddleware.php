<?php

namespace App\Http\Middleware;

use Closure;
use App\StatistikHome;

class StatistikHomeMiddleware
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
        try {
            StatistikHome::firstOrCreate(['date' => now()->format('Y-m-d')])->increment('hit');
            return $next($request);
        } catch (\Throwable $th) {
            return $next($request);
        }
    }
}
