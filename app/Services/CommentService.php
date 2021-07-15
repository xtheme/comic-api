<?php

namespace App\Services;

use App\Models\CommentLike;
use App\Models\User;
use App\Traits\CacheTrait;
use Illuminate\Support\Facades\Cache;

class CommentService
{
    use CacheTrait;

    /**
     * 检查冷却时间
     */
    public function check_cool_down()
    {
        // todo 錯字
        $cool_down = getConfig('comment' ,'colddown');

        $cache_key = $this->getCacheKeyPrefix() . sprintf('comment:cd-%s', request()->user->id);

        $cache_time = Cache::get($cache_key);

        if (isset($cache_time) && ($cache_time + $cool_down) > time()) {
            return false;
        }

        return true;
    }

    /**
     * 检查每日评论数
     */
    public function check_frequency()
    {

        $comment_frequency = getConfig('comment' ,'frequency');

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
     */
    public function update_cache()
    {
        $cache_key = $this->getCacheKeyPrefix() . sprintf('comment:cd-%s', request()->user->id);
        Cache::set($cache_key , time());

        $cache_key = $this->getCacheKeyPrefix() . sprintf('comment:frequency-%s', request()->user->id);
        Cache::increment($cache_key);
    }

    /**
     * 评论点赞
     */
    public function like($comment_id)
    {
        $like = CommentLike::firstOrCreate([
            'user_id'    => request()->user->id,
            'comment_id' => $comment_id,
        ]);

        if (!$like->wasRecentlyCreated) {
            return false;
        }

        return true;
    }

    /**
     * 取得自己的评论
     */
    public function getMyComments($page)
    {
        $page_size = 20;
        
        return User::with(['comments' => function ($query) use ($page , $page_size) {
            $query->skip(($page-1)*$page_size)->take($page_size)->orderBydesc('created_at');
        }])->where('id' , request()->user->id )->get();
    }

}
