<?php

namespace App\Models;

use App\Enums\VideoOptions;
use Spatie\Tags\HasTags;

class Video extends BaseModel
{
    use HasTags;

    protected $guarded = [
        'id',
        'created_at',
        'updated_at',
        'source_platform',
        'source_id',
    ];

    /*public function visit_histories()
    {
        return $this->hasMany('App\Models\VideoVisit', 'video_id', 'id');
    }

    public function play_histories()
    {
        return $this->hasMany('App\Models\VideoPlayLog', 'video_id', 'id');
    }*/

    public function getTaggedTagsAttribute()
    {
        return $this->tags->where('suggest', 1)->sortByDesc('order_column')->take(3)->pluck('name')->toArray();
    }

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
        if (!$value) return '';

        return config('api.video.img_domain') . $value;
    }
}
