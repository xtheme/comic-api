<?php

namespace App\Models;

class Notice extends BaseModel
{
    protected $fillable = [
        'title',
        'image',
        'content',
        'sort',
        'status',
    ];
}
