<?php

namespace App\Models;

use Conner\Tagging\Taggable;

class Video extends BaseModel
{
    use Taggable;

    protected $fillable = [
        'title',
        'author',
        'description',
        'cover',
        'ribbon',
        'status',
    ];

    protected $appends = [
        'tagged_tags'
    ];

    protected $hidden = [
        'tagged'
    ];

    public function series()
    {
        return $this->hasMany('App\Models\VideoSeries');
    }

    public function getTaggedTagsAttribute()
    {
        return $this->tagged->pluck('tag_name')->toArray();
    }
}
