<?php

namespace App\Http\Middleware;

use App\Models\ApiRequestLog;
use Closure;
use Illuminate\Http\Request;

class ApiRequestLogging
{
    /**
     * Handle an incoming request.
     *
     * @param  Request  $request
     * @param  Closure  $next
     *
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        return $next($request);
    }

    /**
     * Handle tasks after the response has been sent to the browser.
     *
     * @param  Request  $request
     *
     * @return void
     */
    public function terminate(Request $request)
    {
        if (config('app.debug') && defined('LARAVEL_START')) {
            $log = new ApiRequestLog;
            $log->user_id = $request->user() ? $request->user()->id : null;
            $log->fingerprint = $request->header('uuid') ?? null;
            $log->ip = $request->ip();
            $log->host = $request->getSchemeAndHttpHost();
            $log->path = $request->path();
            $log->params = $request->input();
            $log->times = $this->getElapsedTimeInMs();
            $log->save();
        }
    }

    protected function getElapsedTimeInMs(): float
    {
        $time = (microtime(true) - LARAVEL_START);
        return round($time, 4);
    }

}
