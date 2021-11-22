<?php

namespace App\Models;

use Gateway;

class Payment extends BaseModel
{
    protected $guarded = [
        'id',
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'pay_options' => 'array',
        'order_options' => 'array',
    ];

    public function packages()
    {
        return $this->belongsToMany(Pricing::class);
    }

    // 調用 SDK
    public function initGateway()
    {
        $sdk = app($this->sdk);

        $sdk->init([
            'payment_id' => $this->id,
            'api_url' => $this->url,
            'app_id' => $this->app_id,
            'app_key' => $this->app_key,
            'pay_options' => $this->pay_options,
        ]);

        return $sdk;
    }

    // 今日累計充值金額
    public function getDailyRechargeAttribute()
    {
        return Gateway::getDailyLimit($this->id);
    }


}
