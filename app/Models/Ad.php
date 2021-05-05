<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ad extends BaseModel
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
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

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'image_thumb'
    ];

    public function ad_space()
    {
        return $this->hasOne('App\Models\AdSpace', 'id', 'space_id');
    }

    /**
     * 广告图片组合
     *
     * @return string
     */
    public function getImageThumbAttribute()
    {
        // @todo change config
        return getOldConfig('web_config', 'api_url') . $this->image;
        // return getConfig('api_url') . $this->image;
    }


}
