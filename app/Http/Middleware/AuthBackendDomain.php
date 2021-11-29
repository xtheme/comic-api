<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AuthBackendDomain
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $host = $request->getSchemeAndHttpHost();

        if (config('app.env') === 'production' && $host != config('app.url')) {
            abort('500');
        }

        return $next($request);
    }
}
