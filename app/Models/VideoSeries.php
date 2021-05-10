<?php

namespace App\Models;

class VideoSeries extends BaseModel
{
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

    protected $appends = [
        'encrypt_url',
        'play_count',
    ];

    protected $hidden = [
        'cdn',
        'video_domain_id',
        'link',
        'play_histories',
    ];

    protected $casts = [
        'vip' => 'boolean',
    ];

    public function video()
    {
        return $this->belongsTo('App\Models\Video');
    }

    public function cdn()
    {
        return $this->hasOne('App\Models\VideoDomain', 'id', 'video_domain_id');
    }

    public function play_histories()
    {
        return $this->hasMany('App\Models\History', 'minor_id', 'id')->where([
            ['class', 'video'],
            ['type', 'play'],
        ]);
    }

    public function getUrlAttribute()
    {
        return $this->cdn->domain . $this->link;
    }

    public function getEncryptUrlAttribute()
    {
        return $this->cdn->encrypt_domain . $this->link;
    }

    public function getPlayCountAttribute()
    {
        return $this->play_histories->where('major_id', $this->video_id)->count();
    }
}
