<?php

namespace App\Traits;

use App\Models\UserVisitLog;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

trait LogEvent
{
    // 紀錄用戶訪問
    public function visit(Model $model)
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

        return $this;
    }

    // 紀錄用戶收藏
}
