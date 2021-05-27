<?php

namespace App\Services;

use App\Traits\CacheTrait;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class CommentService
{

    use CacheTrait;

    /**
     * 检查冷却时间
     *
     * @param $user_id
     */
    public function check_coll_down($user_id)
    {

        $comment_colldown = getConfig('comment_colldown');

        $colldown_key = $this->getCacheKeyPrefix() . sprintf('comment:colldown-%s', $user_id);
        $cache_time = Cache::get($colldown_key);

        if (isset($cache_time) && ($cache_time + $comment_colldown) > time()) {
            return false;
        }

        return true;
    }

    /**
     * 检查每日评论数
     *
     * @param $user_id
     */
    public function check_frequency($user_id)
    {

        $comment_frequency = getConfig('comment_frequency');

        $cache_key = $this->getCacheKeyPrefix() . sprintf('comment:frequency-%s', $user_id);
        $today_request = Cache::get($cache_key);

        if (!$today_request) {
            $cache_ttl = mktime(24,0,0) - time(); // 今天结束前秒数
            Cache::put($cache_key, 0, $cache_ttl);
        }

        if ($today_request >= $comment_frequency) {
            return false;
        }

        return true;

    }

    /**
     * 更新缓存
     *
     * @param $user_id
     */
    public function update_cache($user_id)
    {

        $colldown_key = $this->getCacheKeyPrefix() . sprintf('comment:colldown-%s', $user_id);
        Cache::set($colldown_key , time());


        $cache_key = $this->getCacheKeyPrefix() . sprintf('comment:frequency-%s', $user_id);
        Cache::increment($cache_key);

    }
    
    
}
