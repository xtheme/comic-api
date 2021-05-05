<?php

namespace App\Models;

use Conner\Tagging\Taggable;
use Illuminate\Support\Str;

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

    public function getCoverAttribute($value)
    {
        // todo change config
        $api_url = getOldConfig('web_config', 'api_url');

        if (Str::endsWith($api_url, '/')) {
            $api_url = substr($api_url, 0, -1);
        }

        return $api_url . $value;
    }
}
