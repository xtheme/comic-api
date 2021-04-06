<?php

namespace App\Services;

use Firebase\JWT\JWT;

class JwtService
{
    /**
     * 获取Token
     *
     * @param  array  $data
     *
     * @return string
     */
    public function getToken(array $data)
    {
        $key = config('api.jwt.key');
        if (is_array($data)) {
            return JWT::encode($data, $key);
        }
        return '';
    }

    /**
     * token验证
     *
     * @param  string  $token
     *
     * @return object
     */
    public function tokenVerify(string $token)
    {
        $key = config('api.jwt.key');
        $leeway = config('api.jwt.leeway');
        $algorithm = config('api.jwt.algorithm');

        JWT::$leeway = $leeway;

        return JWT::decode($token, $key, [$algorithm]);
    }
}
