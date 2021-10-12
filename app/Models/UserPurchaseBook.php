<?php

namespace App\Models;

class UserPurchaseBook extends BaseModel
{
    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }
}
