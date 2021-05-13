<?php

namespace App\Models;

class BindLog extends BaseModel
{
    const UPDATED_AT = null;

    protected $fillable = [
        'mobile',
        'device_user_id',
        'action',
    ];
}
