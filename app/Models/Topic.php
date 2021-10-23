<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class Topic extends BaseModel
{
    protected $fillable = [
        'title',
        'type',
        'filter_id',
        'sort',
        'spotlight',
        'row',
        'limit',
        'status',
    ];

    // 篩選規則
    public function rule()
    {
        return $this->hasOne('App\Models\Filter', 'id', 'filter_id');
    }

    public function getStyleAliasAttribute()
    {
        if (!$this->spotlight) {
            return sprintf('每排%s笔', $this->row);
        }

        return sprintf('聚焦, 每排%s笔', $this->row);
    }
}
