<?php

namespace App\Models;

use App\Enums\VideoOptions;
use App\Traits\HasRanking;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Tags\HasTags;

/**
 * @method static active() get active records
 */
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

    protected $hidden = [
        'deleted_at',
        'source_platform',
        'source_id',
    ];

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', 1);
    }

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
        return $this->tags->pluck('name')->toArray();
    }

    public function getShootingTypeAttribute(): string
    {
        return VideoOptions::SHOOTING[$this->style] ?? '';
    }

    /**
     * 修改器: 番號一律大寫
     */
    public function setNumberAttribute($value)
    {
        $this->attributes['number'] = strtoupper($value);
    }

    public function getCountryAttribute($value): string
    {
        return VideoOptions::COUNTRIES[$value];
    }

    public function getSubtitleAttribute($value): string
    {
        return VideoOptions::SUBTITLE[$value];
    }

    public function getActorAttribute($value): array
    {
        return explode(',', $value);
    }

    // 标签
    public function getKeywordsAttribute($value): array
    {
        return explode(',', $value);
    }

    public function getHlsAttribute($value): string
    {
        if (!$value) {
            return '';
        }

        return getConfig('video', 'hls_domain') . $value;
    }

    public function getCoverAttribute($value): string
    {
        if (!$value) {
            return '';
        }

        return getConfig('video', 'image_domain') . $value;
    }
}
