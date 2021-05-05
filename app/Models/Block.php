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
        'spotlight',
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
        if (!isset($properties['tag']['value'])){
            $properties['tag']['value'] = [];
        }

        $this->attributes['properties'] = json_encode($properties);

    }

}
