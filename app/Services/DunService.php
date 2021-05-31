<?php

namespace App\Services;

use App\Models\BindLog;
use App\Models\Order;
use App\Models\User;
use App\Traits\CacheTrait;
use Cache;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Response;

class DunService
{
    use CacheTrait;

   /**
     * @param $params
     * @return string
     * 生成签名
     */
    private static function generateSignature($params) {
        ksort($params);
        $buff="";
        foreach($params as $key => $value){
            if($value !== null) {
                $buff .= $key;
                $buff .= $value;
            }
        }
        $buff .= config('api.dun.key');
        return md5($buff);
    }

    /**
     * @param $params
     * @return array
     * 转编码
     */
    private static function toUtf8($params) {
        $utf8 = [];
        foreach ($params as $key => $value) {
            $utf8[$key] =
                is_string($value) ?
                    mb_convert_encoding($value, "utf8") :
                    $value;
        }
        return $utf8;
    }

    public static function sendRequest($content) {
        $requestBody["dataId"] = self::createUUID();
        $requestBody["content"] = $content;
        $requestBody["secretId"] = config('api.dun.id');
        $requestBody["businessId"] = config('api.dun.bid');
        $requestBody["version"] = config('api.dun.version');
        $requestBody["timestamp"] = time() * 1000;
        $requestBody["nonce"] = sprintf("%d", rand());
        $requestBody = self::toUtf8($requestBody);
        $requestBody["signature"] = self::generateSignature($requestBody);

        $options = [
            'http' => [
                'header'  => 'Content-type: application/x-www-form-urlencoded',
                'method'  => 'POST',
                'timeout' => 2, // read timeout in seconds
                'content' => http_build_query($requestBody),
            ],
        ];
        $context = stream_context_create($options);

        $resultContent  = file_get_contents(config('api.dun.url'), false, $context);
        $result = json_decode($resultContent, true);

        if(isset($result["result"]["action"]) && $result["result"]["action"] === 0) {
            return true;
        }
        return false;
    }


    public static function createUUID($prefix = ""){
        $str = md5(uniqid(mt_rand(), true));
        $uuid  = substr($str,0,8) . '-';
        $uuid .= substr($str,8,4) . '-';
        $uuid .= substr($str,12,4) . '-';
        $uuid .= substr($str,16,4) . '-';
        $uuid .= substr($str,20,12);
        return $prefix . $uuid;
    }


}
