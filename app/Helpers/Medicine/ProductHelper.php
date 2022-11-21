<?php

namespace App\Helpers\Medicine;

use Illuminate\Support\Str;

class ProductHelper
{
    /**
     * Returns the frontend page url for the given product
     * @param string $name
     * @param int $id
     * @return string
     */
    public static function getFrontendPageUrl(string $name, int $id): string
    {
        $prefix = config('medicine.product_url.prefix');
        $name_without_slashes = Str::replace('/', '_', $name);
        $slug = Str::slug($name_without_slashes, '_');
        return "{$prefix}/{$slug}/{$id}";
    }
}
