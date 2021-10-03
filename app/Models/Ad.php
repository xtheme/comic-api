<?php

namespace App\Models;

use Illuminate\Support\Str;

class Ad extends BaseModel
{
    protected $fillable = [
        'space_id',
        'name',
        'sort',
        'platform',
        'jump_type',
        //'jump_id',
        'url',
        'show_time',
        'image',
        'status'
    ];

    public function space()
    {
        return $this->hasOne('App\Models\AdSpace', 'id', 'space_id');
    }

    public function getImageAttribute($value)
    {
        $api_url = getConfig('app', 'img_url');

        if (true == config('api.encrypt.image')){
            $api_url = getConfig('app', 'webp_url');
        }
    
        if (Str::endsWith($api_url, '/')) {
            $api_url = substr($api_url, 0, -1);
        }
    
        return $api_url . $value;
        
    }



    public function getImageThumbAttribute()
    {
        $api_url = getConfig('app', 'img_url');

        if (Str::endsWith($api_url, '/')) {
            $api_url = substr($api_url, 0, -1);
        }

        return $api_url . $this->getRawOriginal('image');
        
    }

}
