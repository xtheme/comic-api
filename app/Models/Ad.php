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
        $api_url = getOldConfig('web_config', 'api_url') ;

        if (true == config('api.encrypt.image')){
            $api_url = getOldConfig('web_config', 'img_sync_url_password_webp') ;
        }
    
        if (Str::endsWith($api_url, '/')) {
            $api_url = substr($api_url, 0, -1);
        }
    
        return $api_url . $value;
        
    }



    public function getImageThumbAttribute()
    {
        // todo change config
        $api_url = getOldConfig('web_config', 'api_url') ;


        if (Str::endsWith($api_url, '/')) {
            $api_url = substr($api_url, 0, -1);
        }

        return $api_url . $this->getRawOriginal('image');
        
    }



}
