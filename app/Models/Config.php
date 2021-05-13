<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;

class Config extends BaseModel
{
    protected $fillable = [
        'group',
        'name',
        'type',
        'code',
        'content',
    ];

    public function scopeGroup(Builder $query, string $group = null)
    {
        return $query->when($group, function (Builder $query, $group) {
            return $query->where('group', $group);
        });
    }

    public function scopeKeyword(Builder $query, string $keyword = null)
    {
        return $query->when($keyword, function (Builder $query, $keyword) {
            return $query->where('code', 'like', '%' . $keyword . '%')->orWhere('name', 'like', '%' . $keyword . '%');
        });
    }
}
