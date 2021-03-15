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

        $ip = request()->header('X-Forwarded-For') ?: $request->ip();
        $ips = $request->ip().', '. request()->header('X-Forwarded-For');
       
        if (in_array($ip, $blacklisted)) {
            UpdateBlacklistLog::dispatch($ip);
            abort(429);
        } else {
            UpdateAccessLog::dispatch($ip, $ips);
        }

        return $next($request);
    }
}
