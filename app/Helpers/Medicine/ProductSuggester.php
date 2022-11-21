<?php

namespace App\Helpers\Medicine;

use App\Models\PharmIndex\Product;
use App\Models\PharmIndex\ProductCategory;
use App\Models\PharmIndex\ProductSearchItem;
use App\Repositories\PharmIndexRepositoryInterface;
use Illuminate\Support\Collection;

class ProductSuggester
{
    private PharmIndexRepositoryInterface $repository;

    public function __construct(PharmIndexRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Tries to suggest similar products by ATC code,
     * if no ATC code given, falls back to suggestions based on
     * similar prices
     * @param Product $product
     * @return Collection
     */
    public function suggest(Product $product): Collection
    {
        $categories = $this->repository->getCategories();
        $items = [];

        $search_limit = config('medicine.suggester.product.search_limit');
        $suggestion_count = config('medicine.suggester.product.suggestion_count');

        list($main_atc, $sub_atc) = $this->getAtcCodesFromClassificationName($categories, $product);
        list($min_price, $max_price) = $this->getPriceRange($product);

        if ($sub_atc) {
            $search_result = $this->repository->searchProducts(['atc' => $sub_atc, 'per_page' => $search_limit]);
            $items = $search_result->items;
        }

        // +1: (search result can contain itself)
        if (count($items) < ($suggestion_count + 1) && $main_atc) {
            $search_result = $this->repository->searchProducts(['atc' => $main_atc, 'per_page' => $search_limit]);
            $items = $search_result->items;
        }

        // +1: (search result can contain itself)
        if (count($items) < ($suggestion_count + 1)) {
            $search_result = $this->repository->searchProducts([
                'price_from' => $min_price,
                'price_to' => $max_price,
                'per_page' => $search_limit
            ]);

            $items = $search_result->items;
        }

        return collect($items)
            ->filter(function (ProductSearchItem $p) use ($product) {
                return $p->productId !== $product->productId;
            })
            ->shuffle()
            ->take($suggestion_count);
    }

    /**
     * Gets the ATC codes from classification name of the product.
     * Returns the main and the sub ATC codes.
     * @param ProductCategory[] $categories
     * @param Product $product
     * @return string[]
     */
    private function getAtcCodesFromClassificationName(array $categories, Product $product): array
    {
        $classification_names = collect($product->classification[0] ?? null)
            ->pluck('classificationName')
            ->take(2);

        $main_category_name = $classification_names->get(0);
        $sub_category_name = $classification_names->get(1);

        $main_atc = null;
        $sub_atc = null;

        foreach ($categories as $category) {
            if ($category->productCategoryName === $main_category_name) {
                $main_atc = $category->productCategoryId;
            }

            foreach ($category->productCategorySubGroup as $sub_category) {
                if ($sub_category->productCategoryName === $sub_category_name) {
                    $sub_atc = $sub_category->productCategoryId;
                }
            }
        }

        return [$main_atc, $sub_atc];
    }

    /**
     * Returns a price range the search query can use to retrieve similar products
     * @param Product $product
     * @return array
     */
    private function getPriceRange(Product $product): array
    {
        $price_margin = config('medicine.suggester.product.price_margin');

        $min_price = null;
        $max_price = null;

        foreach ($product->packages as $package) {
            $price = $package->informativePrice ?? $package->retailPrice;

            if ($price !== null && ($min_price === null || $price < $min_price)) {
                $min_price = $price;
            }

            if ($price !== null && ($max_price === null || $price > $max_price)) {
                $max_price = $price;
            }
        }

        if ($min_price !== null && $max_price !== null) {
            return [round($min_price * (1 - $price_margin)), round($max_price * (1 + $price_margin))];
        } else {
            return [null, null];
        }
    }
}
