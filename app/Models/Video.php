<?php

namespace App\Models;

use Conner\Tagging\Taggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class Video extends Model
{
    use HasFactory;
    use LogsActivity;
    use Taggable;

    protected $fillable = [
        'title',
        'author',
        'description',
        'cover',
        'ribbon',
        'status',
    ];

    public function series()
    {
        return $this->hasMany('App\Models\VideoSeries');
    }

    public function getTaggedArrAttribute()
    {
        return $this->tagged->pluck('tag_name')->toArray();
    }
}
