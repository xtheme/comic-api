<?php

namespace App\Providers;

use App\Services\AesService;
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

            // 数据加密
            if (true == config('api.encrypt.response')) {
                $response = json_encode($response);
                $aes = new AesService();
                $response = $aes->encrypt($response);
                return response($response, $status);
            }

            return Response::json($response, $status);
        });

        Response::macro('jsonError', function ($message = '', $status = 400) {
            $response = [
                'code' => $status,
                'msg' => $message,
            ];

            // $http_status = ($status < 100 || $status >= 600) ? 400 : $status;

            // 数据加密
            /*if (true == config('api.encrypt.response') && request()->hasHeader('sign')) {
                $response = json_encode($response);
                $aes = new AesService();
                $response = $aes->encrypt($response);
                return response($response, $status);
            }*/

            return Response::json($response, $status);
        });
    }
}
