<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;

class User extends BaseModel
{
    use Notifiable;

    // protected $table = 'users_clone';
    // todo User 欄位調整
    const CREATED_AT = 'create_time';
    const UPDATED_AT = 'update_time';

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
        'password',
        'device_id',
        'token',
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
        // 'create_time',
        // 关联统计字段
        'subscribed_status',
        // 'orders_count',
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

    public function orders()
    {
        return $this->hasMany('App\Models\Order');
    }

    public function orders_count()
    {
        return $this->hasOne('App\Models\ViewsOrdersCount');
    }

    public function orders_success_count()
    {
        return $this->hasOne('App\Models\ViewsOrdersSuccessCount');
    }

    public function sign_in()
    {
        return $this->hasOne('App\Models\Sign', 'uid', 'id');
    }

    public function histories()
    {
        return $this->hasMany('App\Models\History', 'user_id', 'id');
    }

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

    public function getIdentityAttribute()
    {
        switch ($this->status) {
            case 1:
                return '<span class="text-success">正常</span>';
            case 2:
                return '<span class="text-danger">禁用</span>';
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

    // public function getOrdersCountAttribute()
    // {
    //     return $this->orders_count()->first()->count ?? 0;
    // }

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
