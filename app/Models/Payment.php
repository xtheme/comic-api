<?php

namespace App\Models;

class Payment extends BaseModel
{
    protected $fillable = [
        'name',
        'url',
        'fee_percentage',
        'library',
        'daily_limit',
        'min_amount',
        'max_amount',
        'pay_options',
        'order_options',
        'status',
    ];

    protected $casts = [
        'pay_options' => 'array',
        'order_options' => 'array',
    ];

    public function packages()
    {
        return $this->belongsToMany(Pricing::class);
    }

    // public function getPriceAttribute($value)
    // {
    //     return floatval($value);
    // }

    // public function getPriceAttribute($value)
    // {
    //     return floatval($value);
    // }
}
