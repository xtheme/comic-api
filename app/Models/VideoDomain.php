<?php

namespace App\Models;

class VideoDomain extends BaseModel
{
    protected $fillable = [
        'title',
        'domain',
        'encrypt_domain',
        'remark',
        'sort',
        'status',
    ];
}
