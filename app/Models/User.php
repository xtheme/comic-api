<?php

namespace App\Models;

use App\Traits\CanPurchase;
use App\Traits\LogEvent;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, LogEvent, CanPurchase;

    protected $guarded = [
        'id',
        'created_at',
        'updated_at',
    ];

    protected $hidden = [
        'password',
        'email_verified_at',
        'remember_token',
        'updated_at',
        'fingerprint',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'subscribed_until',
        'logged_at',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function setEmailAttribute($email)
    {
        $this->attributes['email'] = strtolower($email);
    }

    public function setPasswordAttribute($password)
    {
        $this->attributes['password'] = Hash::make($password);
    }

    // 訂單記錄
    public function orders()
    {
        return $this->hasMany('App\Models\Order');
    }

    // 有效訂單
    public function success_orders()
    {
        return $this->hasMany('App\Models\Order')->where('status', 1);
    }

    // 充值紀錄
    public function recharge_logs()
    {
        return $this->hasMany('App\Models\UserRechargeLog');
    }

    // 購買記錄
    public function purchase_logs()
    {
        return $this->hasMany('App\Models\UserPurchaseLog');
    }

    // 訪問記錄
    public function visit_logs()
    {
        return $this->hasMany('App\Models\UserVisitLog');
    }

    // 收藏記錄
    public function favorite_logs()
    {
        return $this->hasMany('App\Models\UserFavoriteLog');
    }

    // 累計充值金額
    public function getChargeTotalAttribute()
    {
        return $this->success_orders()->sum('amount');
    }

    // 累计漫画消费金币
    public function getPurchaseTotalAttribute()
    {
        return $this->purchase_logs()->sum('item_price');
    }

    // VIP狀態
    public function getIsVipAttribute(): bool
    {
        return Carbon::now()->lt($this->subscribed_until);
    }

    // 查詢用戶使否為首儲
    public function isRenew(): bool
    {
        return Order::where('user_id', $this->id)->where('status', 1)->exists();
    }

    // 更新用戶錢包或VIP時效
    public function saveRecharge(Order $order)
    {
        $coin = $days = 0;
        $coin += $order->plan_options['coin'] ?? 0;
        $coin += $order->plan_options['gift_coin'] ?? 0;
        $days += $order->plan_options['days'] ?? 0;
        $days += $order->plan_options['gift_days'] ?? 0;

        $this->wallet = $this->wallet + $coin;

        if ($days > 0) {
            if ($this->subscribed_until && $this->subscribed_until->greaterThan(Carbon::now())) {
                $this->subscribed_until = $this->subscribed_until->addDays($days);
            } else {
                $this->subscribed_until = Carbon::now()->addDays($days);
            }
        }

        if ($days < 0) {
            if ($this->subscribed_until && $this->subscribed_until->greaterThan(Carbon::now())) {
                $this->subscribed_until = $this->subscribed_until->subDays(abs($days));
            } else {
                $this->subscribed_until = Carbon::now()->subDays(abs($days));
            }
        }

        $this->save();

        $this->logRecharge($order);
    }

    // 建立用戶充值紀錄
    public function logRecharge(Order $order)
    {
        $data = [
            'app_id' => $this->app_id,
            'channel_id' => $this->channel_id,
            'user_id' => $this->id,
            'type' => $order->type,
            'order_id' => $order->order_id,
            'order_no' => $order->order_no,
            'coin' => $order->plan_options['coin'],
            'gift_coin' => $order->plan_options['gift_coin'],
            'days' => $order->plan_options['days'],
            'gift_days' => $order->plan_options['gift_days'],
        ];

        UserRechargeLog::create($data);
    }

    // 後台贈送
    public function saveGift(array $gift)
    {
        $coin = (int) $gift['gift_coin'] ?? 0;
        $days = (int) $gift['gift_days'] ?? 0;

        $this->wallet = $this->wallet + $coin;

        if ($days > 0) {
            if ($this->subscribed_until && $this->subscribed_until->greaterThan(Carbon::now())) {
                $this->subscribed_until = $this->subscribed_until->addDays($days);
            } else {
                $this->subscribed_until = Carbon::now()->addDays($days);
            }
        }

        if ($days < 0) {
            if ($this->subscribed_until && $this->subscribed_until->greaterThan(Carbon::now())) {
                $this->subscribed_until = $this->subscribed_until->subDays(abs($days));
            } else {
                $this->subscribed_until = Carbon::now()->subDays(abs($days));
            }
        }

        $this->save();

        $this->logGift($gift);
    }

    // 建立贈送紀錄
    public function logGift(array $gift)
    {
        $type = 'gift';

        if (0 > $gift['gift_coin'] || 0 > $gift['gift_days']) {
            $type = 'penalty';
        }

        $data = [
            'app_id' => $this->app_id,
            'channel_id' => $this->channel_id,
            'user_id' => $this->id,
            'type' => $type,
            'admin_id' => Auth::user()->id,
            'gift_coin' => $gift['gift_coin'],
            'gift_days' => $gift['gift_days'],
        ];

        UserRechargeLog::create($data);
    }

    public function hasPurchased($type, $item_id)
    {
        if ($this->is_vip) {
            return true;
        }

        return $this->purchase_logs()->where('type', $type)->where('item_id', $item_id)->exists();
    }
}
