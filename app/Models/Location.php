<?php

namespace App\Models;

class Location extends BaseModel
{
    protected $fillable = [
        'name',
        'phone',
        'email',
        'address',
        'description',
        'map',
        'sort',
    ];
}
