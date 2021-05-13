<?php

namespace App\Models;

class Order extends BaseModel
{
    protected $table = 'orders';

    protected $dates = [
        'created_at',
        'updated_at',
        'transaction_at',
    ];

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }
}
