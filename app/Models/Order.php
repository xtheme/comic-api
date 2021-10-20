<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;

class Order extends BaseModel
{
    protected $fillable = [
        'order_no',
        'user_id',
        'type' ,
        'amount',
        'currency',
        'plan_options',
        'payment_id',
        'transaction_id',
        'transaction_at',
        'status',
        'ip',
        'platform',
        'version',
        'first',
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
