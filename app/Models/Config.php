<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;

class Config extends BaseModel
{
    public function scopeCode(Builder $query, string $code = null)
    {
        return $query->when($code, function (Builder $query, $code) {
            return $query->where('code', $code);
        });
    }

    public function scopeKeyword(Builder $query, string $keyword = null)
    {
        return $query->when($keyword, function (Builder $query, $keyword) {
            return $query->where('code', 'like', '%' . $keyword . '%')->orWhere('name', 'like', '%' . $keyword . '%');
        });
    }

    public function getOptionsAttribute($value)
    {
        $value = json_decode($value, true);

        ksort($value);

        return $value;
    }
}
