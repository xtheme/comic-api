<?php

namespace App\Models;

use App\Enums\BookOptions;
use App\Traits\HasRanking;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Tags\HasTags;

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
    public function chapters()
    {
        return $this->hasMany('App\Models\BookChapter');
    }

    public function last_chapter()
    {
        return $this->hasOne('App\Models\BookChapter')->where('status', 1)->latest('episode');
    }

    /**
     * 訪問 BookObserver
     */
    public function visit_logs()
    {
        return $this->hasMany('App\Models\UserVisitLog', 'item_id');
    }

    /**
     * 收藏 BookObserver
     */
    public function favorite_logs()
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
    public function getChargeAttribute()
    {
        // return $this->chapters->where('price', '>', 0)->count() > 0;
        return (bool) $this->last_chapter->price > 0;
    }

    /**
     * 查詢最新章節時間
     */
    public function getReleaseAtAttribute()
    {
        if ($this->last_chapter) {
            return $this->last_chapter->created_at->format('Y-m-d');
        }

        return '';
    }

    /**
     * 直幅封面
     */
    public function getVerticalCoverAttribute($value)
    {
        if (!$value) {
            return '';
        }

        return getImageDomain() . $value;
    }

    /**
     * 横向封面
     */
    public function getHorizontalCoverAttribute($value)
    {
        if (!$value) {
            return '';
        }

        return getImageDomain() . $value;
    }

    /**
     * 數字格式化
     */
    public function getVisitAttribute($value)
    {
        return shortenNumber($value);
    }

    public function getTypeAttribute($value)
    {
        $types = BookOptions::TYPE_OPTIONS;

        return $types[$value];
    }

    public function getReleaseStatusStyleAttribute()
    {
        $types = [
            1 => 'success',
            0 => 'primary',
        ];

        return $types[$this->end];
    }

    public function getReleaseStatusAttribute()
    {
        $types = [
            1 => '已完结',
            0 => '连载中',
        ];

        return $types[$this->end];
    }
}
