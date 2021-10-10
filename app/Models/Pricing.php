<?php

namespace App\Models;

use App\Enums\PricingOptions;

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
}
