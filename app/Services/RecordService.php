<?php

namespace App\Services;

use App\Models\UserVisitBook;
use App\Models\VideoPlayLog;
use App\Models\VideoVisit;

class RecordService
{
    public $type = 'video';

    public function from($type)
    {
        $this->type = $type;

        return $this;
    }

    public function visit($target_id)
    {
        $user = auth('sanctum')->user() ?? null;

        if ($user) {
            switch ($this->type) {
                case 'video':
                    $history = VideoVisit::firstOrCreate([
                        'video_id' => $target_id,
                        'series_id' => 0,
                        'user_id' => $user->id,
                    ]);

                    if (!$history->wasRecentlyCreated) {
                        $history->touch();
                    }
                    break;
                case 'book':
                    $history = UserVisitBook::firstOrCreate([
                        'book_id' => $target_id,
                        'chapter_id' => 0,
                        'user_id' => $user->id,
                    ]);

                    if (!$history->wasRecentlyCreated) {
                        $history->touch();
                    }
                    break;
            }
        }

        // 排行榜
        app(RankingService::class)->from($this->type)->record($target_id);
    }

    public static function play($video_id, $series_id)
    {
        $history = VideoPlayLog::firstOrCreate([
            'video_id'  => $video_id,
            'series_id' => $series_id,
            'user_id'   => request()->user()->id,
            'vip'       => request()->user()->is_vip ? 1 : -1,
        ]);

        if (!$history->wasRecentlyCreated) {
            $history->touch();
        }
    }
}
