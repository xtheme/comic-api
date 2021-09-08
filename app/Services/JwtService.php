<?php

namespace App\Services;

use Firebase\JWT\JWT;
use Illuminate\Support\Str;

class JwtService
{
    /**
     * 获取Token
     *
     * @param  string  $iss
     *
     * @return string
     */
    public static function getToken(string $sub)
    {
        $secret = config('api.jwt.secret');

        $payload = [
            'iss' => request()->server('HTTP_HOST'), //该JWT的签发者
            'sub' => $sub, // 面向的用户
            'iat' => time(), // 签发时间
            'exp' => time() + config('api.jwt.ttl'), // 过期时间
            'nbf' => time() + 1, // 该时间之前不接收处理该Token
            'jti' => Str::uuid(), // 该Token唯一标识
            'ip'  => request()->ip(),
        ];

        return JWT::encode($payload, $secret);
    }

    /**
     * token验证
     *
     * @param  string  $token
     *
     * @return object
     */
    public static function tokenVerify(string $token)
    {
        $key = config('api.jwt.secret');
        $leeway = config('api.jwt.leeway');
        $algorithm = config('api.jwt.algorithm');

        JWT::$leeway = $leeway;

        return JWT::decode($token, $key, [$algorithm]);
    }
}
