<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\FeedBack
 *
 * @property int $id
 * @property string $contact 联系方式
 * @property string $content 反馈内容
 * @property int $addtime
 * @property string $token APP反馈时才有
 * @property int $uid 反馈者ID
 * @property int $type 1安卓 2 ios 3H5
 * @property string $version 版本号
 * @property string|null $title 标题
 * @property-read \App\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder|FeedBack newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FeedBack newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FeedBack query()
 * @method static \Illuminate\Database\Eloquent\Builder|FeedBack whereAddtime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FeedBack whereContact($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FeedBack whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FeedBack whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FeedBack whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FeedBack whereToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FeedBack whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FeedBack whereUid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FeedBack whereVersion($value)
 * @mixin \Eloquent
 */
class FeedBack extends Model
{
    use HasFactory;
    protected $table = 'feedback';

    public function user()
    {
        return $this->hasOne('App\Models\User', 'id', 'uid');
    }



}
