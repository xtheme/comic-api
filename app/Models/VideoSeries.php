<?php

namespace App\Models;

class VideoSeries extends BaseModel
{
    protected $fillable = [
        'video_id',
        'episode',
        'title',
        'vip',
        'status',
        'video_domain_id',
        'link',
        'length',
    ];

    public function video()
    {
        return $this->belongsTo('App\Models\Video');
    }

    public function cdn()
    {
        return $this->hasOne('App\Models\VideoDomain', 'id', 'video_domain_id');
    }

    public function getUrlAttribute()
    {
        return $this->cdn->domain . $this->link;
    }

    public function getEncryptUrlAttribute()
    {
        return $this->cdn->encrypt_domain . $this->link;
    }
}
