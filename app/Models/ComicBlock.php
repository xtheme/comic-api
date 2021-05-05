<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ComicBlock extends BaseModel
{
    use HasFactory;

    protected $table = 'recom_icon';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'icon',
        'listorder',
        'addtime',
        'display',
        'style',
        'display'
    ];


    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'icon_thumb',
        'display_style',
    ];

    /**
     * style_status
     *
     * @return string
     */
    public function getStyleStatusAttribute ()
    {
        $styleArray = [1 => '一排显示1个', 2 => '一排显示2个' , 3 => '一排显示3个', 4 => '广告图'];

        return $styleArray[$this->style];
    }

    /**
     * icon_thumb
     *
     * @return string
     */
    public function getIconThumbAttribute()
    {
        return getConfig('api_url') . '/' . $this->icon;
    }


    /**
     * display_status
     *
     * @return string
     */
    public function getDisplayStatusAttribute()
    {
        $displayArray = [0 => '<span class="text-danger">隐藏</span>', 1 => '<span class="text-success">显示</span>' ];

        //特殊判断
        if ($this->id == 33 || $this->title == '兴趣推荐'){
            return '<span class="text-danger">（特殊类型）启动页使用</span>';
        }


        return $displayArray[$this->display];
    }

}
