<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;

class VerifyLocation
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
        if ($request->headers->has('CF-IPCountry')) {
            $location = $request->header('CF-IPCountry');
            $white_locations = explode(',', getConfig('app', 'white_locations'));
            if (!in_array($location, $white_locations)) {
                Log::emergency('被屏蔽的請求來自: ' . $location, $request->all());
                return Response::jsonError('你的所在地不在本服务的区域内，敬请见谅！', 403);
            }
        }

        return $next($request);
    }
}
