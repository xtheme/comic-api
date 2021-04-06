<?php

namespace App\Traits;

trait CacheTrait
{
    /**
     * 隨機 5 ~ 15 分鐘
     *
     * @param  int  $min
     * @param  int  $max
     *
     * @return int
     * @throws \Exception
     */
    public function getRandomTtl($min = 5, $max = 15)
    {
        return random_int(60 * $min, 60 * $max);
    }

    /**
     * Redis Key 使用版本號作為 prefix, 規避緩存在版本更新後造成汙染
     *
     * @param  null  $version
     *
     * @return string
     */
    public function getCacheKeyPrefix($version = null)
    {
        $version = $version ?? request()->header('app-version');

        return sprintf('v%s:', $version);
    }
}
