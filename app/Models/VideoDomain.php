<?php

namespace App\Models;

class VideoDomain extends BaseModel
{
    protected $fillable = [
        'title',
        'domain',
        'encrypt_domain',
        'remark',
        'sort',
        'status',
    ];

    public function series()
    {
        return $this->hasMany('App\Models\VideoSeries', 'video_domain_id');
    }
}
