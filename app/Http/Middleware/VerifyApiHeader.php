<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Validation\Rule;
use Validator;

class VerifyApiHeader
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
        $data = [
            'platform' => $request->header('platform'),
            'app_version' => $request->header('app-version'),
            'ip' => $request->header('ip'),
        ];

        $validator = Validator::make($data, [
            'platform' => [
                'required',
                'regex:/^(1|2)$/',
            ],
            'app_version' => [
                'required',
                'regex:/^([1-9]\d|[1-9])(\.([1-9]\d|\d)){2}$/',
            ],
            'ip' => [
                'required',
                'ip',
            ],
        ]);

        if ($validator->fails()) {
            return Response::jsonError($validator->errors()->first(), 500);
        }

        return $next($request);
    }
}
