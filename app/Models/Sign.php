<?php

namespace App\Models;


class Sign extends BaseModel
{
    protected $fillable = [
        'user_id',
        'created_at'
    ];

    public function user()
    {
        return $this->hasOne('App\Models\User', 'id', 'uid');
    }
}
