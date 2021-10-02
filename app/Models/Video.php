<?php

namespace App\Models;

use App\Enums\VideoOptions;
use Spatie\Tags\HasTags;

class Video extends BaseModel
{
    use HasTags;

    protected $fillable = [
        'title',
        'description',
        'url',
        'video_cdn_id',
        'cover',
        'length',
        'ribbon',
        'status',
        'mosaic',
        'style',
        'subtitle',
        'number',
        'producer',
        'actor',
        'published_at',
    ];

    protected $appends = [
        // 'tagged_tags',
        // 'visit_count',
        // 'play_count',
    ];

    protected $hidden = [
        // 'tagged',
        // 'visit_histories',
        // 'play_histories',
    ];

    // public function series()
    // {
    //     return $this->hasMany('App\Models\VideoSeries');
    // }

    public function getCountryAttribute($value)
    {
        return VideoOptions::COUNTRIES[$value];
    }

    public function getSubtitleAttribute($value)
    {
        return VideoOptions::SUBTITLE[$value];
    }

    public function getUrlAttribute($value)
    {
        return config('api.video.hls_domain') . $value;
    }

    public function getCoverAttribute($value)
    {
        if (!$value) return null;

        return config('api.video.img_domain') . $value;
    }
}
