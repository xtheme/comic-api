<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Feedback extends Model
{
    use HasFactory;

    /**
     * 關聯用戶
     */
    public function user()
    {
        return $this->morphTo('App\Models\User');
    }
}
