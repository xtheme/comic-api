<?php

namespace App\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;

class BooleanCast implements CastsAttributes
{
    public function get($model, $key, $value, $attributes)
    {
        return $value == 1;
    }

    public function set($model, $key, $value, $attributes)
    {
        return [$key => $value];
    }
}