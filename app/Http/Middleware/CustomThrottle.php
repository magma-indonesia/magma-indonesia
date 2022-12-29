<?php

namespace App\Http\Middleware;

use RuntimeException;
use Illuminate\Routing\Middleware\ThrottleRequests;
use Illuminate\Support\Facades\URL;

class CustomThrottle extends ThrottleRequests
{
    protected function resolveRequestSignature($request)
    {
        if (auth()->check()) {
            $this->limiter = 1000;
        }

        if ($request->route())
            return sha1(URL::current() . '|' . $request->ip());

        throw new RuntimeException('Unable to generate the request signature. Route unavailable.');
    }
}
