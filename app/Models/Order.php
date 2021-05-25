<?php

namespace App\Models;

class Order extends BaseModel
{
    protected $table = 'orders';

    protected $fillable = [
        'user_id',
        'mobile',
        'type' ,
        'name',
        'label',
        'days',
        'amount',
        'status',
        'ip',
        'platform',
        'app_version',
        'first',
        'transaction_at',
    ];

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
