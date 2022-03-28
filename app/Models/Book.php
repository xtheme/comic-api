<?php

namespace App\Models;

use App\Enums\BookOptions;
use App\Traits\HasRanking;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Tags\HasTags;

/**
 * @method static active() get active books
 */
class Book extends BaseModel
{
    use SoftDeletes, HasTags, HasRanking;

    protected $guarded = [
        'id',
        'created_at',
        'updated_at',
    ];

    /**
     * 章節 BookObserver
     */
    public function chapters(): HasMany
    {
        return $this->hasMany('App\Models\BookChapter');
    }

    public function last_chapter(): HasOne
    {
        return $this->hasOne('App\Models\BookChapter')->where('status', 1)->latest('episode');
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

    /**
     * 透過章節查詢本書是否收費
     */
    public function getTaggedTagsAttribute()
    {
        return $this->tags->pluck('name')->toArray();
    }

    /**
     * 透過章節查詢本書是否收費
     */
    public function getChargeAttribute(): bool
    {
        if ($this->last_chapter) {
            return (bool) $this->last_chapter->price > 0;
        }

        return false;
    }

    /**
     * 查詢最新章節時間
     */
    public function getReleaseAtAttribute(): string
    {
        if ($this->last_chapter) {
            return $this->last_chapter->created_at->format('Y-m-d');
        }

        return '';
    }

    /**
     * 直幅封面
     */
    public function getCoverAttribute($value): string
    {
        if (!$value) {
            return '';
        }

        return getConfig('comic', 'image_domain') . $value;
    }

    /**
     * 數字格式化
     */
    public function getVisitAttribute($value): string
    {
        return shortenNumber($value);
    }

    public function getTypeAttribute($value): string
    {
        $types = BookOptions::TYPE_OPTIONS;

        return $types[$value];
    }

    public function getReleaseStatusStyleAttribute(): string
    {
        $types = [
            1 => 'success',
            0 => 'primary',
        ];

        return $types[$this->end];
    }

    public function getReleaseStatusAttribute(): string
    {
        $types = [
            1 => '已完结',
            0 => '连载中',
        ];

        return $types[$this->end];
    }

    // 标签
    public function getKeywordsAttribute($value): array
    {
        return explode(',', $value);
    }
}
