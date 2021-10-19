<?php

namespace App\Models;

class Ad extends BaseModel
{
    protected $fillable = [
        'space_id',
        'sort',
        'url',
        'banner',
        'status'
    ];

    protected $hidden = [
        'space_id',
        'sort',
        'status',
        'created_at',
        'updated_at',
    ];

    public function space()
    {
        return $this->hasOne('App\Models\AdSpace', 'id', 'space_id');
    }

    public function getBannerAttribute($value)
    {
        if (!$value) return '';

        return getImageDomain() . $value;
    }

}
