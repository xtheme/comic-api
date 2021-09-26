<?php

namespace App\Models;

// use Conner\Tagging\Taggable;
use Illuminate\Support\Str;

class Video extends BaseModel
{
    // use Taggable;

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
        if (!$value) return '';

        $img_domain = getOldConfig('web_config', 'api_url') ;
        // $img_domain = getConfig('app', 'img_url');

        if (true == config('api.encrypt.image')){
            $img_domain = getOldConfig('web_config', 'img_sync_url_password_webp') ;
            // $img_domain = getConfig('app', 'webp_img_url') ;

        }

        $img_domain = cleanDomain($img_domain);
    
        return $img_domain . $value;
        
    }

    public function getCoverThumbAttribute()
    {
        $cover = $this->getRawOriginal('cover');

        if (!$cover) return '';

        // todo change config
        $img_domain = getOldConfig('web_config', 'api_url');
        // $img_domain = getConfig('app', 'img_url');

        $img_domain = cleanDomain($img_domain);

        return $img_domain . $cover;
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
