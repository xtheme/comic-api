<?php

namespace App\Models;

class VideoVisit extends BaseModel
{
    protected $fillable = [
        'video_id',
        'series_id',
        'user_id',
    ];

    public function book()
    {
        return $this->hasOne('App\Models\Video', 'id', 'video_id');
    }
}
