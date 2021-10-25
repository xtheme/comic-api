<?php

namespace App\Models;

class Channel extends BaseModel
{
    protected $fillable = [
        'code',
        'description',
        'safe_landing',
    ];
}
