<?php

namespace App\Models;

use App\Enums\VideoOptions;
use App\Traits\HasRanking;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Tags\HasTags;
use Spatie\Tags\Tag;

class Video extends BaseModel
{
    use SoftDeletes, HasTags, HasRanking;

    protected $guarded = [
        'id',
        'created_at',
        'updated_at',
        'source_platform',
        'source_id',
    ];

    /**
     * 訪問 BookObserver
     */
    public function visit_logs(): HasMany
    {
        return $this->hasMany('App\Models\UserVisitLog', 'item_id');
    }

    /**
     * 收藏 BookObserver
     */
    public function favorite_logs(): HasMany
    {
        return $this->hasMany('App\Models\UserFavoriteLog', 'item_id');
    }

    public function getTaggedTagsAttribute()
    {
        // return $this->tags->where('suggest', 1)->sortByDesc('order_column')->take(3)->pluck('name')->toArray();
        return $this->tags->pluck('name')->toArray();
    }

    public function getCountryAttribute($value): string
    {
        return VideoOptions::COUNTRIES[$value];
    }

    public function getSubtitleAttribute($value): string
    {
        return VideoOptions::SUBTITLE[$value];
    }

    public function getUrlAttribute($value): string
    {
        return config('api.video.hls_domain') . $value;
    }

    public function getCoverAttribute($value): string
    {
        if (!$value) return '';

        return config('api.video.img_domain') . $value;
    }
}
