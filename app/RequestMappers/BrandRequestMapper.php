<?php

namespace App\RequestMappers;

use App\Models\Brand;
use App\Models\Media;

class BrandRequestMapper
{
    /**
     * @param Brand $brand
     * @param array<string, mixed> $data
     * @return Brand
     */
    public function map(Brand $brand, array $data): Brand
    {
        $brand->fill([
            'title' => data_get($data, 'title'),
            'url' => data_get($data, 'url'),
            'description' => data_get($data, 'description'),
            'where_to_find' => data_get($data, 'where_to_find'),
            'created_by' => data_get($data, 'created_by'),
            'updated_by' => data_get($data, 'updated_by'),
        ]);

        if (data_get($data, 'image.id')) {
            $brand->image()->associate(Media::findOrFail($data['image']['id']));
        }

        $brand->save();

        $brand->load(['products', 'tags']);

        return $brand;
    }
}
