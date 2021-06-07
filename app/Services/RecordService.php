<?php

namespace App\Services;

use App\Models\BookVisit;
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
        switch ($this->type) {
            case 'video':
                $history = VideoVisit::firstOrCreate([
                    'video_id' => $target_id,
                    'series_id' => 0,
                    'user_id' => request()->user->id,
                ]);

                if (!$history->wasRecentlyCreated) {
                    $history->touch();
                }
                break;
            case 'book':
                $history = BookVisit::firstOrCreate([
                    'book_id' => $target_id,
                    'chapter_id' => 0,
                    'user_id' => request()->user->id,
                ]);

                if (!$history->wasRecentlyCreated) {
                    $history->touch();
                }
                break;
        }
    }

    public static function play($video_id, $series_id)
    {
        $history = VideoPlayLog::firstOrCreate([
            'video_id'  => $video_id,
            'series_id' => $series_id,
            'user_id'   => request()->user->id,
            'vip'       => request()->user->subscribed_status ? 1 : -1,
        ]);

        if (!$history->wasRecentlyCreated) {
            $history->touch();
        }
    }
}
