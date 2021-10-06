<?php

namespace App\Models;

use App\Enums\VideoOptions;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Movie extends Model
{
    use HasFactory;

    public function getTitleAttribute()
    {
        $title = $this->video_name;

        if ($this->number) {
            $title = sprintf('%s %s', $this->number, $title);
        }

        return $title;
    }

    public function getCountryAttribute($value)
    {
        return VideoOptions::COUNTRIES[$value];
    }

    public function getSubtitleAttribute($value)
    {
        return VideoOptions::SUBTITLE[$this->subtitle_type];
    }

    public function getHlsUrlAttribute()
    {
        return config('api.video.hls_domain') . $this->url;
    }

    public function getThumbAttribute()
    {
        if (!$this->preview_pics) return null;

        return config('api.video.img_domain') . $this->preview_pics;
    }

    public function getTagsAttribute($value)
    {
        if (!$value) return null;

        $ids = explode(',', $value);

        return MovieTag::whereIn('id', $ids)->pluck('name')->toArray();
    }
}