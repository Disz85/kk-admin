<?php

namespace App\Http\Controllers\Api;

use App\Http\Actions\Api\FilterAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\ProductListRequest;
use App\Http\Resources\Api\ProductCollection;
use App\Http\Resources\Api\ProductResource;
use App\Models\Product;

class ProductController extends Controller
{
    /**
     * Display a listing of products.
     *
     * @OA\Get(
     *    tags={"Products API"},
     *    path="/api/products",
     *    @OA\Parameter(
     *        name="page",
     *        in="query",
     *        description="Page number",
     *        @OA\Schema(type="integer"),
     *        allowEmptyValue="true"
     *    ),
     *    @OA\Parameter(
     *        name="per_page",
     *        in="query",
     *        description="Page size",
     *        @OA\Schema(type="integer"),
     *        allowEmptyValue="true"
     *    ),
     *    @OA\Response(
     *        response=200,
     *        description="Display a listing of products.",
     *        @OA\JsonContent(ref="#/components/schemas/Product")
     *    ),
     *    @OA\Response(
     *        response=404,
     *        description="No products.",
     *        @OA\JsonContent()
     *    )
     * )
     * @param ProductListRequest $request
     * @param FilterAction $filterAction
     * @return ProductCollection
     */
    public function index(ProductListRequest $request, FilterAction $filterAction): ProductCollection
    {
        if ($filters = $request->validated('filter')) {
            $filteredQuery = $filterAction($filters);
        }

        $query = Product::searchQuery($filteredQuery ?? null)
            ->sort($request->getSortBy(), $request->getSortDirection())
            ->load(['categories.ancestors', 'brand', 'ingredients', 'image']);

        $paginated = $query->paginate(
            perPage: $request->validated('per_page', 20),
            page: $request->validated('page', 1),
        );

        $paginated->data = $paginated->onlyModels()->transform(function ($model) {
            return new ProductResource($model);
        });

        return new ProductCollection($paginated);
    }

    /**
     * Display the specified product.
     *
     * @OA\Get(
     *     tags={"Products API"},
     *     path="/api/products/{product}",
     *     @OA\Parameter(
     *         name="product",
     *         in="path",
     *         required=true,
     *         description="Product UUID",
     *         @OA\Schema(type="string"),
     *     ),
     *    @OA\Response(
     *        response=200,
     *        description="Display a selected product.",
     *        @OA\JsonContent(ref="#/components/schemas/Product")
     *    ),
     *    @OA\Response(
     *        response=404,
     *        description="Product not found.",
     *        @OA\JsonContent(ref="#/components/schemas/Product")
     *    )
     * )
     *
     * @param Product $product
     * @return ProductResource
     */
    public function show(Product $product): ProductResource
    {
        return new ProductResource($product);
    }
}
