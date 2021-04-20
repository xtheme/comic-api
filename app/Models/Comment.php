<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Comment
 *
 * @property int $id
 * @property string|null $post_table 评论内容所在表，不带表前缀
 * @property int $post_id 评论内容 id
 * @property string|null $url 原文地址
 * @property int $uid 发表评论的用户id
 * @property int $to_uid 被评论的用户id
 * @property int $createtime 评论时间
 * @property string $content 评论内容
 * @property int $pid 被回复的评论id
 * @property string|null $path 路径
 * @property int $status 状态，1已审核，0未审核
 * @property int $zan
 * @property-read \App\Models\BookChapter|null $bookchapter
 * @property-read mixed $status_text
 * @property-read \App\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder|Comment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Comment newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Comment query()
 * @method static \Illuminate\Database\Eloquent\Builder|Comment whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Comment whereCreatetime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Comment whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Comment wherePath($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Comment wherePid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Comment wherePostId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Comment wherePostTable($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Comment whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Comment whereToUid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Comment whereUid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Comment whereUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Comment whereZan($value)
 * @mixin \Eloquent
 */
class Comment extends Model
{
    use HasFactory;

    public function user()
    {
        return $this->hasOne('App\Models\User', 'id', 'uid');
    }

    public function book_chapter()
    {
        return $this->hasOne('App\Models\BookChapter', 'id', 'post_id');
    }

    public function getStatusTextAttribute ()
    {

        switch ($this->status) {
            case 1:
                return '<span class="text-success">已通过</span>';
            case 0:
                return '<span class="text-muted">待审核</span>';
            case 2:
                return '<span class="text-danger">已拒绝</span>';
            default:
                return '<span class="text-muted">未知</span>';
        }
    }

}
