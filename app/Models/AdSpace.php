<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdSpace extends BaseModel
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
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
