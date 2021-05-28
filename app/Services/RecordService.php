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
                $where = [
                    'video_id'  => $target_id,
                    'series_id' => 0,
                    'user_id'   => request()->user->id,
                ];
                $model = VideoVisit::where($where)->get()->first();
                if ($model) {
                    $model->touch();
                } else {
                    VideoVisit::create($where);
                }
                break;
            case 'book':
                $where = [
                    'book_id'  => $target_id,
                    'chapter_id' => 0,
                    'user_id'   => request()->user->id,
                ];
                $model = VideoVisit::where($where)->get()->first();
                if ($model) {
                    $model->touch();
                } else {
                    BookVisit::create($where);
                }
                break;
        }
    }

    public static function play($video_id, $series_id)
    {
        $where = [
            'video_id'  => $video_id,
            'series_id' => $series_id,
            'user_id'   => request()->user->id,
            'vip'       => request()->user->subscribed_status ? 1 : -1,
        ];
        $model = VideoPlayLog::where($where)->get()->first();
        if ($model) {
            $model->touch();
        } else {
            VideoPlayLog::create($where);
        }
    }
}
