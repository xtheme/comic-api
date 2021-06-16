<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class LogActivity
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
        $request_route = $request->route()->getName();

        $abilities = ['store', 'update', 'destroy', 'export', 'editable', 'batch', 'transfer', 'unbind'];
        $arr = explode('.', $request_route);
        $action = last($arr);
        if (count($arr) > 2 && Str::endsWith($action, $abilities)) {
            activity()->useLog('后台')->causedBy(Auth::user())->log(__('permissions.' . $arr[2]) . __('permissions.' . $arr[1]));
        }

        return $next($request);
    }
}
