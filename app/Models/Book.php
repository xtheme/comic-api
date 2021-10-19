<?php

namespace App\Models;

use App\Traits\CacheTrait;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Tags\HasTags;

class Book extends BaseModel
{
    use CacheTrait, SoftDeletes, HasTags;

    protected $fillable = [
        'title',
        'author',
        'description',
        'end',
        'vertical_cover',
        'horizontal_cover',
        'type',
        'status',
        'review',
        'operating',
    ];

    public function chapters()
    {
        return $this->hasMany('App\Models\BookChapter')->where('status', 1)->latest('episode');
    }

    public function latest_chapter()
    {
        return $this->hasOne('App\Models\BookChapter')->where('status', 1)->latest('episode');
    }

    /**
     * 查询收费章节数量, 用来判定漫画是否收费
     */
    public function charge_chapters()
    {
        return $this->hasMany('App\Models\BookChapter')->where('status', 1)->where('price', '>', 0);
    }

    /**
     * 訪問關聯
     */
    public function visit_histories()
    {
        return $this->hasMany('App\Models\UserVisitBook');
    }

    /**
     * 收藏關聯
     */
    public function favorite_histories()
    {
        return $this->hasMany('App\Models\UserFavoriteBook');
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
        return $this->chapters->where('price', '>', 0)->count() > 0;
    }

    /**
     * 查詢最新章節時間
     */
    public function getReleaseAtAttribute()
    {
        if ($this->chapters->first()) {
            return $this->chapters->first()->created_at->format('Y-m-d');
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
        $types = [
            1 => '日漫',
            2 => '韩漫',
            3 => '写真',
        ];

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
