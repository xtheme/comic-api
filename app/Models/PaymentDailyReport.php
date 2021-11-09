<?php

namespace App\Models;

class PaymentDailyReport extends BaseModel
{
    protected $guarded = [
        'id',
        'created_at',
        'updated_at',
    ];

    public function payment()
    {
        return $this->belongsTo('App\Models\Payment', 'payment_id');
    }
}
