<?php

namespace App\Traits;

use App\Models\RankingLog;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Str;

trait HasRanking
{
    private $redis;
    private $class_name;

    public static function bootHasRanking()
    {
    }

    public function logRanking()
    {
        $this->redis = Redis::connection('readonly');
        $this->class_name = Str::snake(Str::singular(class_basename($this)));

        // 日週排行 (Redis)
        $this->daily();
        $this->weekly();

        // 年月排行 (MySQL)
        $this->month();

        return $this;
    }

    // 日排行
    private function daily()
    {
        $redis_key = $this->class_name . ':ranking:day:' . date('Y:m:d');

        if (!$this->redis->exists($redis_key)) {
            $this->redis->zadd($redis_key, 1, $this->id);
            // $this->redis->expire($redis_key, 86400);
        } else {
            $this->redis->zincrby($redis_key, 1, $this->id);
        }
    }

    // 週排行
    private function weekly()
    {
        $redis_key = $this->class_name . ':ranking:week:' . date('Y:W');

        if (!$this->redis->exists($redis_key)) {
            $this->redis->zadd($redis_key, 1, $this->id);
        } else {
            $this->redis->zincrby($redis_key, 1, $this->id);
        }
    }

    // 月排行
    private function month()
    {
        $log = RankingLog::firstOrCreate([
            'type' => $this->class_name,
            'item_model' => get_class($this),
            'item_id' => $this->id,
            'year' => date('Y'),
            'month' => date('m'),
        ]);

        // views +1
        $log->increment('views');
    }
}
