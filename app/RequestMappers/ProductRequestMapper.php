<?php

namespace App\RequestMappers;

use App\Models\Media;
use App\Models\Product;
use App\Models\User;

class ProductRequestMapper
{
    public function map(Product $product, array $data): Product
    {
        $product->fill([
            'name' => data_get($data, 'name'),
            'canonical_name' => data_get($data, 'canonical_name'),
            'slug' => data_get($data, 'slug'),
            'price' => data_get($data, 'price'),
            'size' => data_get($data, 'size'),
            'where_to_find' => data_get($data, 'where_to_find'),
            'description' => data_get($data, 'description'),
            'active' => data_get($data, 'active'),
            'hidden' => data_get($data, 'hidden'),
            'sponsored' => data_get($data, 'sponsored'),
            'is_18_plus' => data_get($data, 'is_18_plus'),
            'created_by' => data_get($data, 'created_by'),
        ]);

        if (isset($data['image_id']) && $data['image_id']) {
            $product->image()->associate(Media::findOrFail($data['image_id']));
        }
        if (isset($data['created_by']) && $data['created_by']) {
            $product->user()->associate(User::findOrFail($data['created_by']));
        }

        $product->save();

        $product->categories()->sync(data_get($data, 'categories'));
        $product->tags()->sync(data_get($data, 'tags'));

        $product->load('categories')->load('tags');
        $product->refresh();

        return $product;
    }
}
