<?php

namespace App\Models;

use Illuminate\Support\Str;

class Block extends BaseModel
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'sort',
        'focus',
        'row',
        'causer',
        'properties',
        'status',
    ];


    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        // 'created_split'
    ];

    protected $casts = [
        'properties' => 'array',
    ];

    /**
     * 写入动画跟漫画的种类别
     */
    public function setCauserAttribute($value)
    {
        $this->attributes['causer'] = sprintf('App\Models\%s', Str::ucfirst($value));
    }

    /**
     * json_encode 后写入特性条件
     *
     * @return string
     */
    public function setPropertiesAttribute($properties)
    {
        //tags特性額外處理空值
        if (!isset($properties[3]['value'])){
            $properties[3]['value'] = [];
        }

        $this->attributes['properties'] = json_encode($properties);

    }


    /**
     * 广告图片组合
     *
     * @return string
     */
    public function getImageThumbAttribute()
    {
        return getConfig('api_url');
    }

    /**
     * 特性条件 时间区间 - 时间切割
     *
     * @return string
     */
    public function getCreatedSplitAttribute()
    {
        return explode('-' , $this->properties[2]['value']);
    }

    /**
     * 特性条件 tag - 字串切割
     *
     * @return string
     */
    public function getTagsSplitAttribute()
    {
        return explode('-' , $this->properties[3]['value']);
    }

}
