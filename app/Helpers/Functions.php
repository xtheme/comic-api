<?php

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
