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
        'tagged_tags',
        // 'visit_count',
        // 'play_count',
    ];

    protected $hidden = [
        'tagged',
        'visit_histories',
        'play_histories',
    ];

    public function series()
    {
        return $this->hasMany('App\Models\VideoSeries');
    }

    public function visit_histories()
    {
        return $this->hasMany('App\Models\VideoVisit', 'video_id', 'id');
    }

    public function play_histories()
    {
        return $this->hasMany('App\Models\VideoPlayLog', 'video_id', 'id');
    }

    public function getTaggedTagsAttribute()
    {
        // return $this->tagged->pluck('tag_name')->toArray();
        return $this->tags->where('suggest', 1)->sortByDesc('priority')->take(3)->pluck('name')->toArray();
    }

    public function getCoverAttribute($value)
    {
        $api_url = getOldConfig('web_config', 'api_url') ;

        if (true == config('api.encrypt.image')){
            $api_url = getOldConfig('web_config', 'img_sync_url_password_webp') ;
        }
    
        if (Str::endsWith($api_url, '/')) {
            $api_url = substr($api_url, 0, -1);
        }
    
        return $api_url . $value;
        
    }


    public function getCoverThumbAttribute($value)
    {
        // todo change config
        $api_url = getOldConfig('web_config', 'api_url');

        if (Str::endsWith($api_url, '/')) {
            $api_url = substr($api_url, 0, -1);
        }

        return $api_url . $this->getRawOriginal('cover');
    }

    // public function getVisitCountAttribute()
    // {
    //     return $this->visit_histories->count();
    // }
    //
    // public function getPlayCountAttribute()
    // {
    //     return $this->play_histories->count();
    // }
}
