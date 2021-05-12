<?php

use App\Models\Config;
use App\Repositories\TagRepository;

if (!function_exists('getConfig')) {
    /**
     * @param $key
     *
     * @return string|array
     */
    function getConfig($key)
    {
        $cache_key = 'config:' . $key;

        return Cache::rememberForever($cache_key, function () use ($key) {
            $config = Config::keyword($key)->firstOrFail();
            return $config->content;
        });
    }
}

if (!function_exists('getOldConfig')) {
    /**
     * @param $type
     * @param $key
     *
     * @return string|array
     */
    function getOldConfig($type, $key)
    {
        $cache_key = 'old_config:' . $type;

        return Cache::remember($cache_key, 300, function () use ($type, $key) {
            $config = DB::table('config')->where('config_type', $type)->first();
            $configs = json_decode($config->config_content);
            return $configs->$key;
        });
    }
}

if (!function_exists('webp')) {
    /**
     * webp 图片规格
     *
     * @param  string  $file_path
     * @param  string  $webp_width
     *
     * @return string
     */
    function webp(string $file_path, string $webp_width = '$w540')
    {
        $extension = strrchr($file_path, '.');
        $file_name = explode($extension, $file_path)[0];
        return $file_name . $webp_width . $extension;
    }
}

if (!function_exists('shortenNumber')) {
    /**
     * 数字大於4位數, 以万為单位重新格式化, 取小數點一位, 無條件進位
     */
    function shortenNumber($number)
    {
        if ($number < 10000) {
            $str = $number;
            $suffix = '';
        } else {
            $str = number_format($number / 10000, 1);
            $suffix = '万';
        }

        return $str . $suffix;
    }
}

if (!function_exists('clearLength')) {
    /**
     * 將視頻秒數轉換為易懂的片長
     */
    function clearLength($seconds)
    {
        return gmdate('H:i:s', $seconds);
    }
}

if (!function_exists('starMask')) {
    /**
     * 字串星号加密
     *
     * @param $str
     * @param  int  $first_keep
     * @param  int  $end_keep
     *
     * @return string
     */
    function starMask($str, $first_keep = 3, $end_keep = 3)
    {
        // 获取字符串长度
        $len = mb_strlen($str, 'utf-8');
        //如果字符创长度小于2，不做任何处理
        if ($len <= 2) {
            return $str;
        }

        // mb_substr — 获取字符串的部分
        $firstStr = mb_substr($str, 0, $first_keep, 'utf-8');
        $lastStr = mb_substr($str, -$end_keep, $end_keep, 'utf-8');

        return $firstStr . str_repeat("*", $len - ($first_keep + $end_keep)) . $lastStr;
    }
}

if (!function_exists('checkHex')) {
    /**
     * 私有 16进制检测
     *
     * @param $file
     *
     * @return bool
     */
    function checkHex($file)
    {
        if (file_exists($file)) {
            $resource = fopen($file, 'rb');
            $fileSize = filesize($file);
            fseek($resource, 0);
            if ($fileSize > 512) { // 取頭和尾
                $hexCode = bin2hex(fread($resource, 512));
                fseek($resource, $fileSize - 512);
                $hexCode .= bin2hex(fread($resource, 512));
            } else { // 取全部
                $hexCode = bin2hex(fread($resource, $fileSize));
            }
            fclose($resource);
            /* 匹配16進制中的 <% ( ) %> */
            /* 匹配16進制中的 <? ( ) ?> */
            /* 匹配16進制中的 <script | /script> 大小寫亦可 */
            if (preg_match("/(3c25)|(3c3f.*?706870)|(3C534352495054)|(2F5343524950543E)|(3C736372697074)|(2F7363726970743E)/is", $hexCode)) {
                return false;
            }
        }
        return true;
    }
}

if (!function_exists('getSuggestTags')) {

    function getSuggestTags()
    {
        return app(TagRepository::class)->suggest();
    }
}
