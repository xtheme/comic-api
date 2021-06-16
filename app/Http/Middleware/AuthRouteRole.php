<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Permission;

class AuthRouteRole
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

        $need_auth = Permission::where('name', $request_route)->exists();

        if ($need_auth && !Auth::user()->can($request_route)) {
            return redirect()->route('403');
        }

        return $next($request);
    }
}
