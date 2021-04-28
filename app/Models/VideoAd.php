<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VideoAd extends Model
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
        'times',
        'image',
        'status'
    ];

    public function video_ad_space()
    {
        return $this->hasOne('App\Models\VideoAdSpace', 'id', 'space_id');
    }

    /**
     * 广告图片组合
     *
     * @return string
     */
    public function getImageThumbAttribute()
    {

        return getConfig('api_url') . $this->image;
        //return 'http://uatoriginalmanhuapic.ngxs9.app' . $this->image;
    }


}
