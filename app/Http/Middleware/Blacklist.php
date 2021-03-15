<?php

namespace App\Http\Middleware;

use App\Jobs\UpdateAccessLog;
use App\Jobs\UpdateBlacklistLog;
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
            '203.189.89.245',
            '127.0.0.1',
        ];

        $ip_forwarded = request()->header('X-Forwarded-For') ;
        $ip_remote = request()->ip();

        if (in_array($ip_remote, $blacklisted)) {
            UpdateBlacklistLog::dispatch($ip_remote);
            abort(429);
        } else {
            UpdateAccessLog::dispatch($ip_remote);
            return $next($request);
        }
        
        if (in_array($ip_forwarded, $blacklisted)) {
            UpdateBlacklistLog::dispatch($ip_forwarded);
            abort(429);
        } else {
            UpdateAccessLog::dispatch($ip_forwarded);
            return $next($request);
        }

        return $next($request);
    }
}
