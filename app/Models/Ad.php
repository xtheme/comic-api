<?php

namespace App\Models;

class Ad extends BaseModel
{
    protected $fillable = [
        'space_id',
        'name',
        'sort',
        'platform',
        'jump_type',
        'url',
        'show_time',
        'image',
        'status'
    ];

    protected $appends = [
        'image_thumb'
    ];

    public function ad_space()
    {
        return $this->hasOne('App\Models\AdSpace', 'id', 'space_id');
    }

    /**
     * 广告图片组合
     */
    public function getImageThumbAttribute()
    {
        // todo change config
        return getOldConfig('web_config', 'api_url') . $this->image;
        // return getOldConfig('web_config', 'api_url') . $this->image;
    }
}
