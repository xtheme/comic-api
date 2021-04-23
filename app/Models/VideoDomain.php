<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VideoDomain extends Model
{
    use HasFactory;

    public function series()
    {
        return $this->hasMany('App\Models\VideoSeries');
    }
}
