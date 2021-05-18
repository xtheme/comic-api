<?php

namespace App\Models;

use App\Traits\CacheTrait;
use Conner\Tagging\Taggable;
use Illuminate\Database\Eloquent\SoftDeletes;

class Book extends BaseModel
{
    use CacheTrait, Taggable, SoftDeletes;

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
        'tagged_tags',
        'charge',
        'release_at',
    ];

    protected $hidden = [
        'tagged',
    ];

    public function chapters()
    {
        return $this->hasMany('App\Models\BookChapter')->where('status', 1)->latest();
    }

    /**
     * 查询收费章节数量, 用来判定漫画是否收费
     */
    public function charge_chapters()
    {
        return $this->hasMany('App\Models\BookChapter')->where('status', 1)->where('charge', 1);
    }

    public function visit_histories()
    {
        return $this->hasMany('App\Models\History', 'major_id', 'id')->where([
            ['class', 'book'],
            ['type', 'visit'],
        ]);
    }

    public function collect_histories()
    {
        return $this->hasMany('App\Models\History', 'major_id', 'id')->where([
            ['class', 'book'],
            ['type', 'visit'],
        ]);
    }

    public function getTaggedTagsAttribute()
    {
        return $this->tagged->pluck('tag_name')->toArray();
    }

    public function getChargeAttribute()
    {
        return $this->chapters->where('charge', 1)->exist();
    }

    public function getReleaseAtAttribute()
    {
        if ($this->chapters->first()) {
            return $this->chapters->first()->created_at->format('Y-m-d');
        }

        return '';
    }

    /**
     * 直幅封面 / 竖向封面
     */
    public function getVerticalThumbAttribute()
    {
        if ($this->operating == 1) {
            if (true == config('api.encrypt.image')) {
                return webp(getOldConfig('web_config', 'img_sync_url_password_webp') . $this->vertical_cover, 0);
            }

            return getOldConfig('web_config', 'api_url') . $this->vertical_cover;
        }

        return getOldConfig('web_config', 'img_sync_url') . $this->vertical_cover;
    }

    /**
     * 橫幅封面 / 横向封面
     */
    public function getHorizontalThumbAttribute()
    {
        if ($this->operating == 1) {
            if (true == config('api.encrypt.image')) {
                return webp(getOldConfig('web_config', 'img_sync_url_password_webp') . $this->horizontal_cover, 0);
            }

            return getOldConfig('web_config', 'api_url') . $this->horizontal_cover;
        }

        return getOldConfig('web_config', 'img_sync_url') . $this->horizontal_cover;
    }

    // public function getViewAttribute($value)
    // {
    //     return shortenNumber($value);
    // }

    // public function getBookChaptertimeAttribute($value)
    // {
    //     return date('Y-m-d', $value);
    // }

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
