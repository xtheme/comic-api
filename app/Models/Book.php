<?php

namespace App\Models;

use App\Traits\CacheTrait;
use Conner\Tagging\Taggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Book
 *
 * @property int $id 小说ID
 * @property string $cate_id 小数所属分类ID
 * @property int $author_id 作者id
 * @property string $book_name 小说名称
 * @property string $pen_name 作者
 * @property string $book_desc 小说简介
 * @property int $book_vip 1表示VIP,2表示免费,3表示付费
 * @property int $book_isend 1表示已经完结,2表示连载,3表示暂停
 * @property string $book_thumb 竖向封面
 * @property string $book_thumb2 横向封面
 * @property int $book_addtime 添加时间
 * @property int $book_updatetime 更新时间
 * @property int $app_show app：1显示：0屏蔽
 * @property int $fx_show 分销：1显示：0屏蔽
 * @property int $xcx_show 小程序：1显示：0屏蔽
 * @property int $book_chaptertime 章节更新时间
 * @property int $check_status 0=待审核,1=审核成功,2=审核失败,3=屏蔽,4=未审核
 * @property int $check_time 第一次上架时间
 * @property int $view 漫画热度
 * @property int $real_view 真实漫画阅读量
 * @property int $book_status 1正常,0已删除
 * @property string $del_time 删除时间
 * @property int $gzzj 关注章节
 * @property string|null $zhuishu 追书人数
 * @property int $ismanhua 是否漫画默认1
 * @property int $zhishu 派单指数
 * @property int $collect 收藏数量
 * @property int $daytj 今日推荐
 * @property int $operating 1手动,2自动
 * @property string $book_thumb_banner 竖向封面banner
 * @property string $book_thumb2_banner 横向封面banner
 * @property int $cartoon_type 类型：1日漫,2韩漫
 * @method static \Illuminate\Database\Eloquent\Builder|Book newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Book newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Book query()
 * @method static \Illuminate\Database\Eloquent\Builder|Book whereAppShow($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Book whereAuthorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Book whereBookAddtime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Book whereBookChaptertime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Book whereBookDesc($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Book whereBookIsend($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Book whereBookName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Book whereBookStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Book whereBookThumb($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Book whereBookThumb2($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Book whereBookThumb2Banner($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Book whereBookThumbBanner($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Book whereBookUpdatetime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Book whereBookVip($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Book whereCartoonType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Book whereCateId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Book whereCheckStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Book whereCheckTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Book whereCollect($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Book whereDaytj($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Book whereDelTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Book whereFxShow($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Book whereGzzj($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Book whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Book whereIsmanhua($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Book whereOperating($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Book wherePenName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Book whereRealView($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Book whereView($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Book whereXcxShow($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Book whereZhishu($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Book whereZhuishu($value)
 * @mixin \Eloquent
 */
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
