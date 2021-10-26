<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;

class Order extends BaseModel
{
    protected $guarded = [
        'id',
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'plan_options' => 'array',
    ];

    protected $dates = [
        'transaction_at',
        'created_at',
        'updated_at',
    ];

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    public function payment()
    {
        return $this->belongsTo('App\Models\Payment');
    }

    public function scopeOrderNo(Builder $query, string $order_no)
    {
        return $query->where('order_no', $order_no);
    }

    //
    public function getGatewayAttribute()
    {
        return $this->payment()->first();
    }

}
