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
            '139.180.219.79',
            '173.208.235.202',
            '194.34.232.241',
            '8.140.126.204',
            '218.28.13.203',
            '8.209.204.208',
            '118.25.17.135',
            '47.107.44.186',
            '124.41.240.203',
            '120.194.55.139',
            '131.255.220.39',
            '128.127.164.156',
            '123.203.156.224',
            '119.2.54.25',
            '103.102.13.15',
            '205.185.114.247'
        ];

        $ip = request()->header('X-Forwarded-For') ?: $request->ip();

        if (in_array($ip, $blacklisted)) {
            UpdateBlacklistLog::dispatch($ip);
            abort(429);
        } else {
            UpdateAccessLog::dispatch($ip);
        }


        return $next($request);
    }
}
