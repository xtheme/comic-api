<?php

namespace App\Models;

class ComicBlock extends BaseModel
{
    protected $table = 'recom_icon';

    public $timestamps = false;

    protected $fillable = [
        'title',
        'icon',
        'listorder',
        'addtime',
        'display',
        'style',
        'display',
    ];

    protected $appends = [
        'icon_thumb',
        'display_style',
    ];

    public function getStyleStatusAttribute()
    {
        $styleArray = [
            1 => '一排显示1个',
            2 => '一排显示2个',
            3 => '一排显示3个',
            4 => '广告图',
        ];

        return $styleArray[$this->style];
    }

    public function getIconThumbAttribute()
    {
        return getConfig('api_url') . '/' . $this->icon;
    }

    public function getDisplayStatusAttribute()
    {
        $displayArray = [
            0 => '<span class="text-danger">隐藏</span>',
            1 => '<span class="text-success">显示</span>',
        ];

        //特殊判断
        if ($this->id == 33 || $this->title == '兴趣推荐') {
            return '<span class="text-danger">（特殊类型）启动页使用</span>';
        }

        return $displayArray[$this->display];
    }
}
