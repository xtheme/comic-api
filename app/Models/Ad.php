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
    ];

    public function space()
    {
        return $this->hasOne('App\Models\AdSpace', 'id', 'space_id');
    }

    /**
     * 广告图片组合
     */
    public function getImageAttribute($value)
    {
        // todo change config
        return getOldConfig('web_config', 'api_url') . $value;
    }
}
