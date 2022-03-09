<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasOne;

class RankingLog extends BaseModel
{
    public $timestamps = false;

    protected $fillable = [
        'type',
        'item_model',
        'item_id',
        'views',
        'year',
        'month',
    ];

    public function book(): HasOne
    {
        return $this->hasOne('App\Models\Book', 'id', 'item_id');
    }

    public function video(): HasOne
    {
        return $this->hasOne('App\Models\Video', 'id', 'item_id');
    }
}
