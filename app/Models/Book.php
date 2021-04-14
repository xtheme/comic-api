<?php

namespace App\Models;

use App\Traits\CacheTrait;
use Conner\Tagging\Taggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasFactory, CacheTrait, Taggable;

    protected $table = 'book';

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'cate_id',  // getCategoriesAttr
        'operating', // 決定圖片路徑
        'book_thumb', // getVerticalThumbAttr
        'book_thumb2', // getHorizontalThumbAttr
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        // 'latest_chapter_title',
        // 'categories',
        // 'vertical_thumb', // 竖向封面
        // 'horizontal_thumb', // 横向封面
    ];

    // protected $dates = [
    //     'created_at',
    //     'updated_at',
    //     'subscribed_at',
    //     'last_login_at',
    // ];

    public function chapters()
    {
        return $this->hasMany('App\Models\BookChapter');
    }

    /**
     * 獲取漫畫類別陣列
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    // public function latest_chapter()
    // {
    //     return $this->hasOne('App\Models\BookChapter')->latest('updatetime');
    // }

    /**
     * 獲取漫畫類別陣列
     *
     * @return array
     */
    public function getCategoriesAttribute()
    {
        $ids = explode(',', $this->cate_id);

        $categories = BookCategory::query()->select(['id', 'name'])->where('status', 1)->whereIn('id', $ids)->get();

        $categories = $categories->mapWithKeys(function($category) {
            return [$category->id => $category->name];
        })->toArray();

        return $categories;
    }

    /**
     * 直幅封面 / 竖向封面
     *
     * @return string
     */
    public function getVerticalThumbAttribute()
    {
        if ($this->operating == 1) {
            if (true == config('api.encrypt.image')) {
                return webp(getConfig('img_sync_url_password_webp') . $this->book_thumb, 0);
            }

            return getConfig('api_url') . $this->book_thumb;
        }

        return getConfig('img_sync_url') . $this->book_thumb;
    }

    /**
     * 橫幅封面 / 横向封面
     *
     * @return string
     */
    public function getHorizontalThumbAttribute()
    {
        if ($this->operating == 1) {
            if (true == config('api.encrypt.image')) {
                return webp(getConfig('img_sync_url_password_webp') . $this->book_thumb2, 0);
            }

            return getConfig('api_url') . $this->book_thumb2;
        }

        return getConfig('img_sync_url') . $this->book_thumb2;
    }

    public function getViewAttribute($value)
    {
        return numberToWords($value);
    }

    public function getBookChaptertimeAttribute($value)
    {
        return date('Y-m-d', $value);
    }

    public function getCartoonTypeAttribute($value)
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
            1 => 'primary',
            2 => 'success',
            3 => 'danger',
        ];

        return $types[$this->book_isend];
    }

    public function getReleaseStatusAttribute()
    {
        $types = [
            1 => '已完结',
            2 => '连载中',
            3 => '暂停',
        ];

        return $types[$this->book_isend];
    }
}
