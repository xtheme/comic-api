<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Notice
 *
 * @property int $id
 * @property string $notice_title 公告标题
 * @property string|null $notice_keyword 公告关键字
 * @property string $notice_content 公告内容
 * @property int $time 创建时间
 * @property string $dyestuff 字体颜色
 * @property int $is_copy 是否复制：0否 1是
 * @property int $edition_type 版本：1旧,2新1.1.7后版本
 * @property string $copy_content 需要复制的内容
 * @property-read mixed $type
 * @method static \Illuminate\Database\Eloquent\Builder|Notice newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Notice newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Notice query()
 * @method static \Illuminate\Database\Eloquent\Builder|Notice whereCopyContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Notice whereDyestuff($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Notice whereEditionType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Notice whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Notice whereIsCopy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Notice whereNoticeContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Notice whereNoticeKeyword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Notice whereNoticeTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Notice whereTime($value)
 * @mixin \Eloquent
 */
class Notice extends Model
{
    use HasFactory;
    protected $table = 'notice';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'notice_title',
        'notice_keyword',
        'notice_content',
        'dyestuff',
        'edition_type',
        'copy_content',
        'is_copy',
        'time'
    ];


    /**
     * type
     *
     * @return string
     */
    public function getTypeAttribute ()
    {

        switch ($this->edition_type) {
            case 2:
                return '<span class="text-success">新版</span>';
            case 1:
                return '<span class="text-danger">旧版</span>';
        }

    }
}
