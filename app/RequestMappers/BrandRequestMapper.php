<?php

namespace App\RequestMappers;

use App\Models\Brand;
use App\Models\Media;

class BrandRequestMapper
{
    public function map(Brand $brand, array $data): Brand
    {
        $brand->fill([
            'title' => data_get($data, 'title'),
            'slug' => data_get($data, 'slug'),
            'url' => data_get($data, 'url'),
            'where_to_find' => data_get($data, 'where_to_find'),
            'approved' => data_get($data, 'approved'),
            'description' => data_get($data, 'description'),
        ]);

        if (array_key_exists('image', $data) && array_key_exists('id', $data['image']) && $data['image']['id'] !== null) {
            $brand->image()->associate(Media::findOrFail($data['image']['id']));
        }

        $brand->save();

        return $brand;
    }
}
