<?php

namespace App\Http\Middleware;

use App\Blacklist as AppBlacklist;
use App\Jobs\UpdateAccessLog;
use App\Jobs\UpdateBlacklistLog;
use Closure;
use Illuminate\Support\Facades\Cache;
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
        $blacklisted = Cache::remember('blacklist', 720, function () {
            return AppBlacklist::pluck('ip_address')->toArray();
        });

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
