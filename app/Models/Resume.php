<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Resume extends Model
{
    use HasFactory;

    public function city()
    {
        return $this->hasOne('App\Models\ResumeCity');
    }

    public function getTagAttribute($value)
    {
        if (!$value) return null;

        return explode(',', $value);
    }

    public function getPicturesAttribute($value)
    {
        if (!$value) return [];

         $pictures = json_decode($value);

         return collect($pictures)->map(function ($pic) {
             return config('api.resume.img_domain') . $pic;
         });
    }
}
