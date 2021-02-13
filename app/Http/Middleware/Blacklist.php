<?php

namespace App\Http\Middleware;

use App\Jobs\UpdateAccessLog;
use Closure;
use Illuminate\Support\Facades\URL;

class Blacklist
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
        $blacklisted = [
            '104.21.44.208',
            '192.227.75.86',
        ];

        $ip = request()->header('X-Forwarded-For') ?: $request->ip();

        UpdateAccessLog::dispatch($ip, URL::full());
        
        if (in_array($ip, $blacklisted))
            abort(403);

        return $next($request);
    }
}
