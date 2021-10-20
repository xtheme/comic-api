<?php

namespace App\Traits;

use App\Models\UserVisitLog;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

trait HasRecord
{
    public function makeRecord(Model $model) {

    }

    // 紀錄用戶訪問模型 (漫畫/視頻)
    public function logVisit(Model $model)
    {
        $class = get_class($model);

        $type = Str::snake(Str::singular(class_basename($class)));

        $log = UserVisitLog::firstOrCreate([
            'user_id' => $this->id,
            'type' => $type,
            'item_model' => $class,
            'item_id' => $model->getKey(),
        ]);

        // 如果不是剛剛創建的則更新 updated_at (影響排序)
        if (!$log->wasRecentlyCreated) {
            $log->touch();
        }
    }

    //
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
