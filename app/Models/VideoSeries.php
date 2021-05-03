<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class VideoSeries extends Model
{
    use HasFactory;
    use LogsActivity;

    protected $fillable = [
        'video_id',
        'episode',
        'title',
        'vip',
        'status',
        'video_domain_id',
        'link',
        'length',
    ];


    public function video()
    {
        return $this->belongsTo('App\Models\Video');
    }

    public function cdn()
    {
        return $this->hasOne('App\Models\VideoDomain', 'id', 'video_domain_id');
    }

    public function getUrlAttribute()
    {
        return $this->cdn->domain . $this->link;
    }

    public function getEncryptUrlAttribute()
    {
        return $this->cdn->encrypt_domain . $this->link;
    }
}
