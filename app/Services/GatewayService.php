<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;

class GatewayService
{
    public function getDailyLimit($payment_id)
    {
        $redis_key = sprintf('payment:gateway:%s:%s', $payment_id, date('Y-m-d'));

        $cache_limit = 0;

        if (Cache::has($redis_key)) {
            $cache_limit = Cache::get($redis_key);
        }

        return $cache_limit;
    }

    /**
     * 添加每日限額
     */
    public function incDailyLimit($payment_id, $amount)
    {
        $cache_key = sprintf('payment:gateway:%s:%s', $payment_id, date('Y-m-d'));
        Cache::increment($cache_key, $amount);
    }

}
