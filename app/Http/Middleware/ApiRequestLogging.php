<?php

namespace App\Http\Middleware;

use App\Models\ApiRequestLog;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ApiRequestLogging
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     *
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // if (defined('LARAVEL_START')) {
        //     $time = $this->getElapsedTimeInMs();
        //     Log::debug(sprintf('IP: %s, Api: %s (Prepare: %s sec)', $request->ip(), $request->fullUrl(), $time));
        // }

        return $next($request);
    }

    /**
     * Handle tasks after the response has been sent to the browser.
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @return void
     */
    public function terminate(Request $request)
    {
        if (defined('LARAVEL_START')) {
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

    protected function getElapsedTimeInMs()
    {
        $time = (microtime(true) - LARAVEL_START);
        return round($time, 4);
    }

}
