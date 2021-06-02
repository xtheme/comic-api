<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class User extends BaseModel
{
    use Notifiable;

    // protected $table = 'users_new';

    // todo User 欄位調整
    // const CREATED_AT = 'create_time';
    // const UPDATED_AT = 'update_time';

    // public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username',
        'device_id',
        'area',
        'mobile',
        'avatar',
        'sex',
        'score',
        'sign',
        'status',
        'platform',
        'version',
        'token',
        'sign_days',
        'signup_ip',
        'last_login_ip',
        'last_login_at',
        'subscribed_at',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'name',
        'nickname',
        'password',
        'email',
        'mobile_bind',
        'email_bind',
        'userface',
        'money',
        'role',
        'group',
        'sort',
        'openid',
        'did',
        'isvip',
        'vipstime',
        'vipetime',
        'fcbl',
        'xingming',
        'fangshi',
        'zhanghao',
        'tgid',
        'guanzhu',
        'ewm',
        'isguanzhu',
        'sxid',
        'isout',
        'gzopenid',
        'agentlogin',
        'payopenid',
        'zjgz',
        'idnumber',
        'qq_id',
        'qq_uid',
        'weibo_id',
        'weixin_uid',
        'weixin_id',
        'auto_buy',
        'user_type',
        'tzurl',
        'device_tokens',
        'cover_img',
        'invite_uid',
        'invite_install_code',
        'version_type',
        'del_comment',
        'total_comment',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        // 訂閱狀態
        'subscribed_status',
        // todo 旧版字段相容
        'integral',
        'is_author',
        'works',
        'praise_num',
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

    public function signs()
    {
        return $this->hasMany('App\Models\Sign', 'user_id', 'id');
    }

    // public function histories()
    // {
    //     return $this->hasMany('App\Models\History', 'user_id', 'id');
    // }

    public function book_visit_histories()
    {
        return $this->hasMany('App\Models\BookVisit', 'user_id', 'id');
    }

    public function video_visit_histories()
    {
        return $this->hasMany('App\Models\VideoVisit', 'user_id', 'id');
    }

    public function video_play_histories()
    {
        return $this->hasMany('App\Models\VideoPlayLogs', 'user_id', 'id');
    }

    /*public function getGenderAttribute()
    {
        switch ($this->sex) {
            case 1:
                return '<span class="text-success">男</span>';
            case 2:
                return '<span class="text-danger">女</span>';
            default:
                return '<span class="text-muted">未知</span>';
        }
    }*/

    /*public function getIdentityAttribute()
    {
        switch ($this->status) {
            case 1:
                return '<span class="text-success">正常</span>';
            case 0:
                return '<span class="text-danger">禁用</span>';
            default:
                return '<span class="text-muted">未知</span>';
        }
    }*/

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
        // $subscribed_at = $this->subscribed_at;
        //
        // if (!$subscribed_at) {
        //     return false;
        // }
        //
        // $now = time();
        //
        // if ($now <= strtotime($subscribed_at)) {
        //     return true;
        // }
        //
        // return false;
        return Carbon::now()->lt($this->subscribed_at);
    }

    public function getAvatarAttribute($value)
    {
        // todo change config
        $api_url = getOldConfig('web_config', 'api_url');

        if (Str::endsWith($api_url, '/')) {
            $api_url = substr($api_url, 0, -1);
        }

        return $api_url . $value;
    }

    /*public function getOsAttribute()
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
    }*/

    /*public function getAccountTypeAttribute()
    {
        return $this->mobile ? '电话' : '设备';
    }*/

    /**
     * 旧版 /v2/dt/myCenter 字段兼容 integral 積分
     *
     * @return int
     */
    public function getIntegralAttribute()
    {
        return 0;
    }

    /**
     * 旧版 /v2/dt/myCenter 字段兼容 is_author 是否為作者
     *
     * @return int
     */
    public function getIsAuthorAttribute()
    {
        return 0;
    }

    /**
     * 旧版 /v2/dt/myCenter 字段兼容 works 作品數
     *
     * @return int
     */
    public function getWorksAttribute()
    {
        return 0;
    }

    /**
     * 旧版 /v2/dt/myCenter 字段兼容 praise 作品讚數
     *
     * @return int
     */
    public function getPraiseNumAttribute()
    {
        return 0;
    }
}
