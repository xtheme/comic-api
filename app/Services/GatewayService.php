<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redis;

class GatewayService
{
    public $redis;

    public function __construct()
    {
        $this->redis = Redis::connection('readonly');
    }

    public function getDailyLimit($payment_id)
    {
        $redis_key = sprintf('payment:gateway:%s:%s', $payment_id, date('Y-m-d'));

        $cache_limit = 0;

        if ($this->redis->exists($redis_key)) {
            $cache_limit = $this->redis->get($redis_key);
        }

        return $cache_limit;
    }

    /**
     * 添加每日限額
     */
    public function incDailyLimit($payment_id, $amount)
    {
        $cache_key = sprintf('payment:gateway:%s:%s', $payment_id, date('Y-m-d'));
        $this->redis->incr($cache_key, $amount);
    }

}
