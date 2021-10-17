<?php

namespace App\Services;

use App\Models\BookRanking;
use Illuminate\Support\Facades\Redis;

class RankingService
{
    public $redis;
    public $type = 'book';

    public function __construct()
    {
        $this->redis = Redis::connection('readonly');
    }

    public function from($type)
    {
        $this->type = $type;

        return $this;
    }

    public function record($id)
    {
        // 日週排行 (Redis)
        $this->daily($id);
        $this->weekly($id);

        // 年月排行 (MySQL)
        $this->month($id);
    }

    // 日排行
    private function daily($id)
    {
        $redis_key = $this->type . ':ranking:day:' . date('Y:m:d');

        if (!$this->redis->exists($redis_key)) {
            $this->redis->zadd($redis_key, 1, $id);
            // $this->redis->expire($redis_key, 86400);
        } else {
            $this->redis->zincrby($redis_key, 1, $id);
        }
    }

    // 週排行
    private function weekly($id)
    {
        $redis_key = $this->type . ':ranking:week:' . date('Y:W');

        if (!$this->redis->exists($redis_key)) {
            $this->redis->zadd($redis_key, 1, $id);
        } else {
            $this->redis->zincrby($redis_key, 1, $id);
        }
    }

    // 月排行
    private function month($id)
    {
        $record = BookRanking::firstOrCreate([
            'book_id' => $id,
            'month' => date('Y-m'),
        ]);

        $record->increment('views');
    }
}
