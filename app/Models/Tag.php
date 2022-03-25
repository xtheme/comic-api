<?php

namespace App\Models;

class Tag extends \Spatie\Tags\Tag
{
    public function tagged_book()
    {
        return $this->hasMany('App\Models\Taggable', 'tag_id', 'id')->where('taggable_type', 'App\Models\Book');
    }

    public function tagged_video()
    {
        return $this->hasMany('App\Models\Taggable', 'tag_id', 'id')->where('taggable_type', 'App\Models\Video');
    }

    public function category()
    {
        return $this->hasOne('App\Models\Category', 'type', 'type');
    }

    public function getCategoryNameAttribute()
    {
        return $this->category->name ?? '';
    }
}
