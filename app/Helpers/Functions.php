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
