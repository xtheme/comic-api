<?php

use App\Models\Category;
use App\Models\Config;
use App\Models\Tag;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use StephenHill\Base58;
use StephenHill\GMPService;

if (!function_exists('getConfigs')) {
    /**
     * @param $key
     *
     * @return string|array
     */
    function getConfigs($code)
    {
        $config = Config::code($code)->first();

        if (!$config) {
            Log::error('配置代号: ' . $code . ' 不存在');
        }

        return $config->options ?? [];
    }
}

if (!function_exists('getConfig')) {
    /**
     * @param $key
     *
     * @return string|array
     */
    function getConfig($code, $key, $default = '')
    {
        $cache_key = sprintf('config:%s:%s', $code, $key);

        if (Cache::has($cache_key)) {
            return Cache::get($cache_key);
        }

        $options = getConfigs($code);

        if (!isset($options[$key])) {
            Log::error('配置項: ' . $code . '.' . $key . ' 不存在');

            return $default;
        }

        Cache::set($cache_key, $options[$key], 600);

        return $options[$key] ?? '';
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

if (!function_exists('videoLength')) {
    /**
     * 將視頻秒數轉換為易懂的片長
     */
    function videoLength($seconds)
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

if (!function_exists('watchDog')) {
    function watchDog($user)
    {
        if ($user->id == 1) {
            return true;
        }

        return null;
    }
}

if (!function_exists('getCategoryByType')) {
    function getCategoryByType($type)
    {
        $cache_key = 'category:' . $type;

        return Cache::remember($cache_key, 300, function () use ($type) {
            $tags = Tag::with(['category'])->where('type', 'like', $type . '%')->orderByDesc('order_column')->get();

            $tags = $tags->mapToGroups(function ($tag) {
                return [$tag['type'] => $tag['name']];
            })->toArray();

            $categories = Category::where('type', 'like', $type . '%')->get();

            return $categories->mapWithKeys(function ($category) use ($tags) {
                return [
                    $category['name'] => [
                        'code' => $category['type'],
                        'tags' => $tags[$category['type']],
                    ],
                ];
            })->toArray();
        });
    }
}

if (!function_exists('parseImgFromHtml')) {
    function parseImgFromHtml($content)
    {
        preg_match_all('/<\s*img\s+[^>]*?src\s*=\s*(\'|\")(.*?)\\1[^>]*?\/?\s*>/i', $content, $matches);

        $img_arr = $matches[0];
        $img = [];

        $img_url = getConfig('app', 'img_url');

        for ($i = 0; $i < count($img_arr); $i++) {
            preg_match('/<img.+(width=\"?\d*\"?).+(height=\"?\d*\"?).+>/i', $img_arr[$i], $match); //匹配宽高

            if (!empty($match)) {
                preg_match('/<\s*img\s+[^>]*?src\s*=\s*(\'|\")(.*?)\\1[^>]*?\/?\s*>/i', $match[0], $match2);
                $match2[2] = str_replace($img_url, '', $match2[2]);
                $img[$i] = $match2[2];
            } else {
                $matches[2][$i] = str_replace($img_url, '', $matches[2][$i]);
                $img[$i] = $matches[2][$i];
            }
        }

        return $img;
    }
}

if (!function_exists('cleanDomain')) {
    /**
     * @param $image
     *
     * @return string
     */
    function cleanDomain($domain)
    {
        if (Str::endsWith($domain, '/')) {
            $domain = substr($domain, 0, -1);
        }

        return $domain;
    }
}

if (!function_exists('getEncryptDomain')) {
    /**
     * @return string
     */
    function getEncryptDomain()
    {
        $list = config('api.encrypt.domains');

        foreach ($list as $domain) {
            try {
                $response = Http::timeout(1)->head($domain);
                if (200 == $response->status()) {
                    return $domain;
                }
            } catch (\Exception $e) {
                continue;
            }
        }

        Log::emergency('所有加密圖片域名都掛了');

        return $list[0];
    }
}

if (!function_exists('getImageUrl')) {
    /**
     * @return string
     */
    function getImageUrl($path)
    {
        $path = Str::of($path)->ltrim('/');

        if (request()->is('api/*')) {
            if (true == config('api.encrypt.image')) {
                // 加密
                $base58 = new Base58(null, new GMPService());
                $encrypted_filename = $base58->encode(sodium_crypto_secretbox($path, config('api.encrypt.nonce'), config('api.encrypt.key')));
                $url =  getEncryptDomain() . '/' . $encrypted_filename . '.html';
            } else {
                // 未加密
                $url = Storage::url($path);
            }
        } else {
            // 後台圖片使用 S3 Presigned URL
            $url = Storage::temporaryUrl($path, now()->addMinutes(10));
        }

        return $url;
    }
}
