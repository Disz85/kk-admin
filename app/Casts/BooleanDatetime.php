<?php

namespace App\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Support\Carbon;

/**
 * Casts the boolean attribute value from frontend (approved) to the database as datetime
 */
class BooleanDatetime implements CastsAttributes
{
    /**
     * Cast the given value.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @param  string  $key
     * @param  mixed  $value
     * @param  array  $attributes
     * @return bool
     */
    public function get($model, $key, $value, $attributes)
    {
        return ($value !== null);
    }

    /**
     * Prepare the given value for storage.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @param  string  $key
     * @param  array  $value
     * @param  array  $attributes
     * @return Carbon
     */
    public function set($model, $key, $value, $attributes)
    {
        return $value ? ($attributes[$key] ?? now()) : null;
    }
}
