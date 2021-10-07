<?php

namespace App\Models;

use Illuminate\Support\Str;

class Ad extends BaseModel
{
    protected $fillable = [
        'space_id',
        'name',
        'sort',
        'platform',
        'jump_type',
        //'jump_id',
        'url',
        'show_time',
        'image',
        'status'
    ];

    public function space()
    {
        return $this->hasOne('App\Models\AdSpace', 'id', 'space_id');
    }

    public function getImageAttribute($value)
    {
        if (!$value) return '';

        return getImageDomain() . $value;
    }

}
