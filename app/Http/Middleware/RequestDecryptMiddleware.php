<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;
use Illuminate\Validation\Rule;
use Validator;

class RequestDecryptMiddleware
{
    /**
     * 解密請求
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     *
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // 若開啟数据加密
        if (true == config('api.encrypt.response')) {
            $encrypted = $request->getContent();

            if (!$this->isBase64($encrypted)) {
                Log::warning('收到未加密的请求');

                return Response::jsonError('Not Acceptable!', 406);
            }

            try {
                $decrypted = Crypt::decryptString($encrypted);
                parse_str($decrypted, $params);
                $request->replace($params);
            } catch (\Exception $e) {
                return Response::jsonError($e->getMessage(), 406);
            }

        }

        return $next($request);
    }

    public function isBase64(string $string)
    {
        if (base64_encode(base64_decode($string, true)) === $string) {
            return true;
        } else {
            return false;
        }
    }
}
