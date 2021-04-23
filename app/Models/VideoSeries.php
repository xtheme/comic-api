<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VideoSeries extends Model
{
    use HasFactory;

    public function video()
    {
        return $this->belongsTo('App\Models\Video');
    }

    public function cdn()
    {
        return $this->hasOne('App\Models\VideoDomain');
    }
}
