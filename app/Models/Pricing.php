<?php

namespace App\Models;

class Pricing extends BaseModel
{
    protected $fillable = [
        'type',
        'name',
        'description',
        'label',
        'price',
        'list_price',
        'coin',
        'gift_coin',
        'days',
        'gift_days',
        'target',
        'sort',
    ];

    // 支付渠道
    public function gateways()
    {
        return $this->belongsToMany(Payment::class);
    }

    public function getGatewayIdsAttribute()
    {
        return $this->gateways()->get()->pluck('id')->toArray();
    }
}
