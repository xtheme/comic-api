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

if (!function_exists('getAllTags')) {

    function getAllTags()
    {
        return app(TagRepository::class)->all();
    }
}

if (!function_exists('parseImgFromHtml')) {
    function parseImgFromHtml($content)
    {
        preg_match_all('/<\s*img\s+[^>]*?src\s*=\s*(\'|\")(.*?)\\1[^>]*?\/?\s*>/i', $content, $matches);

        $img_arr = $matches[0];
        $img = [];
        $width = 500;
        $height = 700;
        // todo change config
        $base_url = getOldConfig('web_config', 'api_url');

        for ($i = 0; $i < count($img_arr); $i++) {
            preg_match('/<img.+(width=\"?\d*\"?).+(height=\"?\d*\"?).+>/i', $img_arr[$i], $match); //匹配宽高

            if (!empty($match)) {
                preg_match('/<\s*img\s+[^>]*?src\s*=\s*(\'|\")(.*?)\\1[^>]*?\/?\s*>/i', $match[0], $match2);
                $match2[2] = str_replace($base_url, '', $match2[2]);
                $img[$i]['url'] = $match2[2];
                $img[$i]['width'] = (int) str_replace('"', '', substr($match[1], 6, strlen($match[1])));
                $img[$i]['height'] = (int) str_replace('"', '', substr($match[2], 7, strlen($match[2])));

                if ($img[$i]['width'] == 0 || $img[$i]['height'] == 0) {
                    try {
                        $get_img_info = getimagesize($base_url . $img[$i]['url']);
                        $img[$i]['width'] = (int) $get_img_info[0];
                        $img[$i]['height'] = (int) $get_img_info[1];
                    } catch (\Exception $exception) {
                        $img[$i]['width'] = $width;
                        $img[$i]['height'] = $height;
                    }
                }
            } else {
                $matches[2][$i] = str_replace($base_url, '', $matches[2][$i]);
                $img[$i]['url'] = $matches[2][$i];

                try {
                    $get_img_info = getimagesize($base_url . $img[$i]['url']);
                    $img[$i]['width'] = (int) $get_img_info[0];
                    $img[$i]['height'] = (int) $get_img_info[1];
                } catch (\Exception $exception) {
                    $img[$i]['width'] = $width;
                    $img[$i]['height'] = $height;
                }
            }
        }

        return $img;
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

if (!function_exists('insertArray')) {
    function insertArray(array $array, array $insert, int $position = 0)
    {
        $num = count($array);

        // 未指定位置时, 将新数据插入至原数组中间
        if ($position == 0) {
            $position = ceil($num / 2);
        }

        // 將元素插入陣列開頭
        if ($position == 1) {
            array_unshift($array, $insert);

            return $array;
        }

        // 將元素插入陣列最后
        if ($position > $num) {
            array_push($array, $insert);

            return $array;
        }

        $new_array = [];

        foreach ($array as $key => $value) {
            array_push($new_array, $value);
            if ($key + 1 == $position) {
                array_push($new_array, $insert);
            }
        }

        return $new_array;
    }
}