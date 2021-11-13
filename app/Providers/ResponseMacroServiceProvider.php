<?php

namespace App\Providers;

use Illuminate\Support\Facades\Crypt;
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
            $response = [
                'code' => $status,
                'msg' => $message,
                'data' => $data,
            ];

            // 若開啟数据加密
            if (true == config('api.encrypt.response') && request()->is('api/*')) {
                return Response::encrypt($response);
            }

            return Response::json($response);
        });

        Response::macro('jsonError', function ($message = '', $status = 400) {
            $response = [
                'code' => $status,
                'msg' => $message,
            ];

            // 若開啟数据加密
            if (true == config('api.encrypt.response') && request()->is('api/*')) {
                return Response::encrypt($response);
            }

            return Response::json($response);
        });

        // 若開啟数据加密, 響應加密後的字串給前端
        Response::macro('encrypt', function ($response = [], $status = 200) {
            $text = json_encode($response, JSON_UNESCAPED_UNICODE);
            $encrypt_text = Crypt::encryptString($text);

            return response($encrypt_text, $status)->header('Content-Type', 'text/plain');
        });
    }
}
