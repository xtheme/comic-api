<?php

namespace App\Traits;

use App\Models\UserVisitLog;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

trait HasRanking
{

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
        $record = DB::table('ranking_'. date('Y'))->firstOrCreate([
            'book_id' => $id,
            'month' => date('Y-m'),
        ]);

        $record->increment('views');
    }
}
