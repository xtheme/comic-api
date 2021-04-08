<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\order
 *
 * @property int $id
 * @property int $user_id 用户ID
 * @property string $mobile 手机号码
 * @property string $type 套餐类型
 * @property string $name 名称
 * @property string $label 标签
 * @property int $days 有效天数
 * @property string $amount 订单金额
 * @property int $status 状态, -1=支付失败, 0=待处理, 1=支付成功
 * @property string|null $ip
 * @property int $platform 手机系统, 1=Android, 2=iOS
 * @property string|null $app_version APP版本号
 * @property int $first 首储=1
 * @property \Illuminate\Support\Carbon|null $created_at 订单成立时间
 * @property string|null $transaction_at 订单交易时间, 金流方回传
 * @property \Illuminate\Support\Carbon|null $updated_at 订单更新时间
 * @method static \Illuminate\Database\Eloquent\Builder|Order newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Order newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Order query()
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereAppVersion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereDays($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereFirst($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereIp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereLabel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereMobile($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order wherePlatform($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereTransactionAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereUserId($value)
 * @mixin \Eloquent
 */
class Order extends Model
{
    use HasFactory;

    protected $table = 'orders';

    protected $dates = [
        'created_at',
        'updated_at',
        'transaction_at',
    ];

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }
}
