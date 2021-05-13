<?php

namespace App\Models;

class AdSpace extends BaseModel
{
    protected $fillable = [
        'remark',
        'class',
        'status',
        'sdk'
    ];

    public function ads()
    {
        return $this->hasMany('App\Models\Ad', 'space_id', 'id')->where('status' , 1);
    }
}
