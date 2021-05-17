<?php

namespace App\Models;

use App\Traits\CacheTrait;
use Conner\Tagging\Taggable;

class Book extends BaseModel
{
    use CacheTrait, Taggable;

    protected $table = 'book';

    const CREATED_AT = 'book_addtime';
    const UPDATED_AT = 'book_updatetime';

    /**
     * todo 可刪除字段
     * 关注章节 gzzj
     * 今日推荐 daytj
     * 派单指数 zhishu
     * book_thumb_banner
     * book_thumb2_banner
     * cate_id
     */

    protected $fillable = [
        'book_name',
        'cartoon_type',
        'book_desc',
        'pen_name',
        'view',
        'collect',
        'book_thumb',
        'book_thumb2',
        'book_isend',
        'operating',
    ];

    protected $hidden = [
        'cate_id',  // getCategoriesAttr
        'operating', // 決定圖片路徑
        'book_thumb', // getVerticalThumbAttr
        'book_thumb2', // getHorizontalThumbAttr
    ];

    public function chapters()
    {
        return $this->hasMany('App\Models\BookChapter')->where('chapter_status', 1);
    }

    /**
     * 查询收费章节数量, 用来判定漫画是否收费
     */
    public function charge_chapters()
    {
        return $this->hasMany('App\Models\BookChapter')->where('chapter_status', 1)->where('isvip', 2);
    }

    /**
     * 獲取漫畫類別陣列
     * todo 換成標籤
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
     */
    public function getVerticalThumbAttribute()
    {
        if ($this->operating == 1) {
            if (true == config('api.encrypt.image')) {
                return webp(getOldConfig('web_config', 'img_sync_url_password_webp') . $this->book_thumb, 0);
            }

            return getOldConfig('web_config', 'api_url') . $this->book_thumb;
        }

        return getOldConfig('web_config', 'img_sync_url') . $this->book_thumb;
    }

    /**
     * 橫幅封面 / 横向封面
     */
    public function getHorizontalThumbAttribute()
    {
        if ($this->operating == 1) {
            if (true == config('api.encrypt.image')) {
                return webp(getOldConfig('web_config', 'img_sync_url_password_webp') . $this->book_thumb2, 0);
            }

            return getOldConfig('web_config', 'api_url') . $this->book_thumb2;
        }

        return getOldConfig('web_config', 'img_sync_url') . $this->book_thumb2;
    }

    public function getViewAttribute($value)
    {
        return shortenNumber($value);
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
