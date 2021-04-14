<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\BookChapter
 *
 * @property int $id
 * @property int|null $book_id 漫画ID
 * @property int|null $volume_id 分卷ID
 * @property int $idx 章节排序id
 * @property string $title 章节标题
 * @property string|null $chapter_thumb 章节封面
 * @property string $content 章节内容
 * @property int|null $isvip 0=非vip章节,1=vip章节,2付费章节
 * @property int $price 章节价格
 * @property int|null $addtime
 * @property int|null $updatetime
 * @property int|null $check_status 0=待审核,1=审核通过,2=审核不通过,3=草稿
 * @property int|null $is_buy 0=未付稿费,1=已付稿费
 * @property int $view
 * @property int|null $status 0 不启用  1启用
 * @property int $chapter_status 1正常,0已删除
 * @property string $del_time 删除时间
 * @property string|null $json_images json 图片
 * @property int|null $shell_status 1图片没有，2只有一张图，3修改了宽高
 * @property int $operating 1手动,2自动
 * @property-read \App\Models\Book|null $book
 * @method static \Illuminate\Database\Eloquent\Builder|BookChapter newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|BookChapter newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|BookChapter query()
 * @method static \Illuminate\Database\Eloquent\Builder|BookChapter whereAddtime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BookChapter whereBookId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BookChapter whereChapterStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BookChapter whereChapterThumb($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BookChapter whereCheckStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BookChapter whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BookChapter whereDelTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BookChapter whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BookChapter whereIdx($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BookChapter whereIsBuy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BookChapter whereIsvip($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BookChapter whereJsonImages($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BookChapter whereOperating($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BookChapter wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BookChapter whereShellStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BookChapter whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BookChapter whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BookChapter whereUpdatetime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BookChapter whereView($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BookChapter whereVolumeId($value)
 * @mixin \Eloquent
 */
class BookChapter extends Model
{
    use HasFactory;

    protected $table = 'chapterlist';

    public function book()
    {
        return $this->hasOne('App\Models\Book', 'id', 'book_id');
    }


}
