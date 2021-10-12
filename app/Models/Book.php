<?php

namespace App\Models;

use App\Traits\CacheTrait;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
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

    protected $appends = [
        //'tagged_tags',
        // 'charge',
        // 'release_at',
    ];

    protected $hidden = [
        'tagged',
    ];

    public function chapters()
    {
        return $this->hasMany('App\Models\BookChapter')->where('status', 1)->latest();
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
        return $this->hasMany('App\Models\BookChapter')->where('status', 1)->where('charge', 1);
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
        // return $this->tagged->pluck('tag_name')->toArray();
        return $this->tags->where('suggest', 1)->sortByDesc('priority')->take(3)->pluck('name')->toArray();
    }

    /**
     * 透過章節查詢本書是否收費
     */
    public function getChargeAttribute()
    {
        return $this->chapters->where('charge', 1)->count() ? 1 : -1;
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
        if (!$value) return '';

        return getImageDomain() . $value;
    }

    /**
     * 横向封面
     */
    public function getHorizontalCoverAttribute($value)
    {
        if (!$value) return '';

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
        ];

        return $types[$value];
    }

    public function getReleaseStatusStyleAttribute()
    {
        $types = [
            1 => 'success',
            -1 => 'primary',
        ];

        return $types[$this->end];
    }

    public function getReleaseStatusAttribute()
    {
        $types = [
            1 => '已完结',
            -1 => '连载中',
        ];

        return $types[$this->end];
    }
}
