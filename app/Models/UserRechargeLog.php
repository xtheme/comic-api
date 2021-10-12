<?php

namespace App\Models;

class UserRechargeLog extends BaseModel
{
    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }
}
