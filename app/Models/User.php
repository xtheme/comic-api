<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Carbon;
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

    // 漫畫購買記錄
    public function purchase_books()
    {
        return $this->hasMany('App\Models\UserPurchaseBook');
    }

    // 累計充值金額
    public function getChargeTotalAttribute()
    {
        return $this->success_orders()->sum('amount');
    }

    // 累计漫画消费金币
    public function getPurchaseBooksTotalAttribute()
    {
        return $this->purchase_books()->sum('coin');
    }

    /**
     * VIP狀態
     */
    public function getIsVipAttribute(): bool
    {
        return Carbon::now()->lt($this->subscribed_until);
    }
}