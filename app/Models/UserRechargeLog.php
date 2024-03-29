<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserRechargeLog extends BaseModel
{
    protected $fillable = [
        'channel_id',
        'user_id',
        'type',
        'admin_id',
        'order_id',
        'order_no',
        'coin',
        'gift_coin',
        'days',
        'gift_days',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo('App\Models\User');
    }
}
