<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

/**
 * App\Models\User
 *
 * @property int $id 用户id
 * @property string $username 用户名
 * @property int $area 區碼
 * @property string $mobile 手机号码
 * @property int $mobile_bind 是否绑定手机号码
 * @property string $userface app用户头像
 * @property int $score 积分/金币
 * @property string|null $sign 个性签名
 * @property string|null $signup_ip 注册ip
 * @property string|null $last_login_at
 * @property string|null $last_login_ip 登录ip
 * @property int|null $status 状态：0禁用，1启用
 * @property int|null $sex 性别，男1女2未知0
 * @property string|null $token app登录标识
 * @property string|null $device_id 设备id（注册游客时）
 * @property int|null $platform 设备平台  1安卓 2为ios
 * @property string $version 版本号
 * @property int|null $del_comment 被删除评论数量
 * @property int|null $total_comment 总评论
 * @property string|null $subscribed_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read mixed $account_type
 * @property-read mixed $gender
 * @property-read mixed $orders_count
 * @property-read mixed $os
 * @property-read string $phone
 * @property-read bool $subscribed_status
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read int|null $notifications_count
 * @method static \Illuminate\Database\Eloquent\Builder|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User query()
 * @method static \Illuminate\Database\Eloquent\Builder|User whereArea($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereDelComment($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereDeviceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereLastLoginAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereLastLoginIp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereMobile($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereMobileBind($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePlatform($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereScore($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereSex($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereSign($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereSignupIp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereSubscribedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereTotalComment($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereUserface($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereUsername($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereVersion($value)
 * @mixin \Eloquent
 */
class User extends Model
{
    use HasFactory, Notifiable;

    protected $table = 'users_clone';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username',
        'avatar',
        'sex',
        'score',
        'status',
        'sign',
        'subscribed_at',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        // 旧版字段相容
        'email_bind',
        'userface',
        'create_time',
        // 关联统计字段
        'subscribed_status',
        'orders_count',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'subscribed_at',
        'last_login_at',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [];

    public function getGenderAttribute()
    {
        switch ($this->sex) {
            case 1:
                return '<span class="text-success">男</span>';
            case 2:
                return '<span class="text-danger">女</span>';
            default:
                return '<span class="text-muted">未知</span>';
        }
    }

    /**
     * 電話號碼
     *
     * @return string
     */
    public function getPhoneAttribute()
    {
        return $this->mobile ? sprintf('%s-%s', $this->area, $this->mobile) : '';
    }

    /**
     * 訂閱狀態
     *
     * @return bool
     */
    public function getSubscribedStatusAttribute()
    {
        $subscribed_at = $this->subscribed_at;

        if (!$subscribed_at) {
            return false;
        }

        $now = time();

        if ($now <= strtotime($subscribed_at)) {
            return true;
        }

        return false;
    }

    public function getOrdersCountAttribute()
    {
        if ($this->mobile != '') {
            $where = [
                'mobile' => sprintf('%s-%s', $this->area, $this->mobile),
            ];
        } else {
            $where = [
                'user_id' => $this->id,
            ];
        }

        return Order::where($where)->count();
    }

    public function getOsAttribute()
    {
        switch ($this->platform) {
            case 1:
                $platform = '<i class="bx bxl-android font-medium-2"></i>';
                break;
            case 2:
                $platform = '<i class="bx bxl-apple font-medium-2"></i>';
                break;
            default:
                $platform = '<span class="text-muted">未知</span>';
                break;
        }

        return $platform;
    }

    public function getAccountTypeAttribute()
    {
        return $this->mobile ? '电话' : '设备';
    }

    /**
     * 旧版字段相容 email_bind
     *
     * @return int
     */
    public function getEmailBindAttribute()
    {
        return $this->mobile ? 1 : 0;
    }
    /**
     * 旧版字段相容 userface
     *
     * @return string
     */
    public function getUserfaceAttribute()
    {
        return $this->avatar;
    }
}
