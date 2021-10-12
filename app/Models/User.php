<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'email_verified_at',
        'remember_token',
        'updated_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    // 訂單記錄
    public function orders()
    {
        return $this->hasMany('App\Models\Order');
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

    // public function getOrdersCountAttribute()
    // {
    //     return $this->orders()->count();
    // }

    // public function getSuccessOrdersCountAttribute()
    // {
    //     return $this->orders()->where('status', 1)->count();
    // }

    // public function comments()
    // {
    //     return $this->hasMany('App\Models\Comment', 'user_id', 'id');
    // }

    public function setPasswordAttribute($password)
    {
        $this->attributes['password'] = Hash::make($password);
    }
}