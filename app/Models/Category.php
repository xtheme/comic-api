<?php

namespace App\Models;

class Category extends BaseModel
{
    protected $guarded = [
        'id',
        'created_at',
        'updated_at',
    ];

    public function tags()
    {
        return $this->hasMany('App\Models\Tag', 'type', 'type');
    }
}
