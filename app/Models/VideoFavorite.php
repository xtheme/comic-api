<?php

namespace App\Models;

class VideoFavorite extends BaseModel
{
    protected $fillable = [
        'video_id',
        'series_id',
        'user_id',
    ];

    public function video()
    {
        return $this->hasOne('App\Models\Video', 'id', 'video_id');
    }

    public function series()
    {
        return $this->hasOne('App\Models\Series', 'id', 'series_id');
    }
}
