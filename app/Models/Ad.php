<?php

namespace App\Models;

use Illuminate\Support\Facades\Storage;

class Ad extends BaseModel
{
    protected $guarded = [
        'id',
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

        return Storage::url($value);
    }

}
