<?php

namespace App\Models;

class VideoPlayLog extends BaseModel
{
    protected $fillable = [
        'video_id',
        'series_id',
        'user_id',
        'vip',
    ];

    public function book()
    {
        return $this->hasOne('App\Models\Video', 'id', 'video_id');
    }

    public function series()
    {
        return $this->hasOne('App\Models\VideoSeries', 'id', 'series_id');
    }
}
