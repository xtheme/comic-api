<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Sign
 *
 * @property int $id
 * @property int $uid 用户id
 * @property int $days 签到天数
 * @property string $addtime 签到时间
 * @property-read \App\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder|Sign newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Sign newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Sign query()
 * @method static \Illuminate\Database\Eloquent\Builder|Sign whereAddtime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Sign whereDays($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Sign whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Sign whereUid($value)
 * @mixin \Eloquent
 */
class Sign extends BaseModel
{

    protected $table = 'sign';
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'days',
        'addtime'
    ];


    public function user()
    {
        return $this->hasOne('App\Models\User' , 'id', 'uid');
    }


}
