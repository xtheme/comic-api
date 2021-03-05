<?php

namespace App\Providers;

use Illuminate\Support\Facades\Response;
use Illuminate\Support\ServiceProvider;

class ResponseMacroServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        Response::macro('jsonSuccess', function ($message = '', $data = [], $status = 200) {
            return Response::json([
                'code' => $status,
                'msg'  => $message,
                'data' => $data,
            ]);
        });

        Response::macro('jsonError', function ($message = '', $status = 400) {
            return Response::json([
                'code' => $status,
                'msg'  => $message,
            ], $status);
        });
    }
}
