<?php

namespace App\Models;

class ChannelDailyReport extends BaseModel
{
    protected $guarded = [
        'id',
        'created_at',
        'updated_at',
    ];

    public function channel()
    {
        return $this->belongsTo('App\Models\Channel', 'id', 'channel_id');
    }
}
