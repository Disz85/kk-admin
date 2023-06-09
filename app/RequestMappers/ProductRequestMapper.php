<?php

namespace App\RequestMappers;

use App\Models\Media;
use App\Models\Product;

class ProductRequestMapper
{
    /**
     * @param Product $product
     * @param array<string, mixed> $data
     * @return Product
     */
    public function map(Product $product, array $data): Product
    {
        $product->fill([
            'name' => data_get($data, 'name'),
            'canonical_name' => data_get($data, 'canonical_name'),
            'price' => data_get($data, 'price'),
            'size' => data_get($data, 'size'),
            'where_to_find' => data_get($data, 'where_to_find'),
            'description' => data_get($data, 'description'),
            'is_active' => data_get($data, 'is_active'),
            'is_sponsored' => data_get($data, 'is_sponsored'),
            'is_18_plus' => data_get($data, 'is_18_plus'),
            'created_by' => data_get($data, 'created_by'),
            'updated_by' => data_get($data, 'updated_by'),
            'brand_id' => data_get($data, 'brand.id'),
            'published_at' => data_get($data, 'published_at'),
            'ingredients_by' => data_get($data, 'ingredients_by'),
        ]);

        if ($product->published_at === null && data_get($data, 'is_active')) {
            $product->published_at = now();
        }

        if (data_get($data, 'image.id')) {
            $product->image()->associate(Media::findOrFail($data['image']['id']));
        }

        $product->save();

        $categories = [];
        if ($productCategory = data_get($data, 'category.id')) {
            $categories[] = $productCategory;
        }

        if ($skinTypes = data_get($data, 'skin_types.*.id')) {
            $categories = array_merge($categories, $skinTypes);
        }

        if ($skinConcerns = data_get($data, 'skin_concerns.*.id')) {
            $categories = array_merge($categories, $skinConcerns);
        }

        if ($hairProblems = data_get($data, 'hair_problems.*.id')) {
            $categories = array_merge($categories, $hairProblems);
        }

        $product->categories()->sync($categories);

        $product->tags()->sync(data_get($data, 'tags.*.id'));

        $product->ingredients()->sync(data_get($data, 'ingredients.*.id'));

        $product->refresh();

        $product->load(['categories', 'tags', 'ingredients']);

        return $product;
    }
}
