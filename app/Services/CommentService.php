<?php

namespace App\Services;

use App\Models\CommentLike;
use App\Traits\CacheTrait;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class CommentService
{

    use CacheTrait;

    /**
     * 检查冷却时间
     *
     */
    public function check_coll_down()
    {

        $comment_colldown = getConfig('comment_colldown');

        $colldown_key = $this->getCacheKeyPrefix() . sprintf('comment:colldown-%s', request()->user->id);
        $cache_time = Cache::get($colldown_key);

        if (isset($cache_time) && ($cache_time + $comment_colldown) > time()) {
            return false;
        }

        return true;
    }

    /**
     * 检查每日评论数
     *
     */
    public function check_frequency()
    {

        $comment_frequency = getConfig('comment_frequency');

        $cache_key = $this->getCacheKeyPrefix() . sprintf('comment:frequency-%s', request()->user->id);
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
     */
    public function update_cache()
    {

        $colldown_key = $this->getCacheKeyPrefix() . sprintf('comment:colldown-%s', request()->user->id);
        Cache::set($colldown_key , time());


        $cache_key = $this->getCacheKeyPrefix() . sprintf('comment:frequency-%s', request()->user->id);
        Cache::increment($cache_key);

    }

    /**
     * 评论点赞
     *
     */
    public function like($comment_id)
    {

        $exists = CommentLike::where([
            'user_id' => request()->user->id , 
            'comment_id' => $comment_id
        ])->exists();
        
        //评论已点赞过
        if ($exists){
            return false;
        }

        CommentLike::create([
            'user_id' => request()->user->id , 
            'comment_id' => $comment_id
        ]);

        return true;

    }


    
    
}
