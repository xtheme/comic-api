<?php

namespace App\Models;

class History extends BaseModel
{
    public $incrementing = false;

    const UPDATED_AT = null;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'major_id',
        'minor_id',
        'user_vip',
        'user_id',
        'type',
        'class',
        'created_at',
    ];

    public function video()
    {
        return $this->hasOne('App\Models\Video', 'id', 'major_id');
    }

    public function book()
    {
        return $this->hasOne('App\Models\Book', 'id', 'major_id');
    }
}
