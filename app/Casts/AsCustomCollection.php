<?php

namespace App\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Casts\ArrayObject;
use Illuminate\Support\Collection;

class AsCustomCollection implements CastsAttributes
{
    public function get($model, $key, $value, $attributes)
    {
        if (is_null($value)) {
            return null;
        }
        return isset($attributes[$key]) ? new Collection(json_decode($attributes[$key], true)) : null;
    }

    public function set($model, $key, $value, $attributes)
    {
        if (is_null($value)) {
            return null;
        }
        return [$key => json_encode($value)];
    }

//    public function serialize($model, string $key, $value, array $attributes)
//    {
//        return $value->getArrayCopy();
//    }
}
