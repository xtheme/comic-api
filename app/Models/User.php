<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'email_verified_at',
        'remember_token',
        'updated_at',
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

    // 購買記錄
    public function visit_books()
    {
        return $this->hasMany('App\Models\UserVisitBook');
    }

    // 累計充值金額
    public function getChargeTotalAttribute()
    {
        return $this->success_orders()->sum('amount');
    }

    // 累计漫画消费金币
    public function getPurchaseTotalAttribute()
    {
        return $this->purchase_logs()->sum('coin');
    }

    // VIP狀態
    public function getIsVipAttribute(): bool
    {
        return Carbon::now()->lt($this->subscribed_until);
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

        if ($this->subscribed_until && $this->subscribed_until->greaterThan(Carbon::now())) {
            $this->subscribed_until = $this->subscribed_until->addDays($days);
        } else {
            $this->subscribed_until = Carbon::now()->addDays($days);
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
        $coin = $days = 0;
        $coin += $gift['gift_coin'] ?? 0;
        $days += $gift['gift_days'] ?? 0;

        $this->wallet = $this->wallet + $coin;

        if ($this->subscribed_until && $this->subscribed_until->greaterThan(Carbon::now())) {
            $this->subscribed_until = $this->subscribed_until->addDays($days);
        } else {
            $this->subscribed_until = Carbon::now()->addDays($days);
        }

        $this->save();

        $this->logGift($gift);
    }

    // 建立贈送紀錄
    public function logGift(array $gift)
    {
        $data = [
            'app_id' => $this->app_id,
            'channel_id' => $this->channel_id,
            'user_id' => $this->id,
            'type' => 'gift',
            'admin_id' => Auth::user()->id,
            'gift_coin' => $gift['gift_coin'],
            'gift_days' => $gift['gift_days'],
        ];

        UserRechargeLog::create($data);
    }
}