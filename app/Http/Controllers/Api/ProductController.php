<?php

namespace App\Http\Controllers\Api;

use App\Http\Actions\Api\Products\FilterAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\ProductListRequest;
use App\Http\Resources\Api\ProductCollection;
use App\Http\Resources\Api\ProductResource;
use App\Models\Product;
use Elastic\ScoutDriverPlus\Builders\SearchParametersBuilder;

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
     *    @OA\Parameter(
     *        name="sort",
     *        in="query",
     *        description="sort_by and sort order ('-' prefix means desc)",
     *        @OA\Schema(type="string"),
     *    ),
     *    @OA\Parameter(
     *        name="filter[name]",
     *        in="query",
     *        description="Name",
     *        @OA\Schema(type="string"),
     *        allowEmptyValue="true"
     *    ),
     *    @OA\Parameter (
     *         name="filter[product_categories][0]",
     *         in="query",
     *         description="product_category 1 uuid",
     *         @OA\Schema(type="string"),
     *         allowEmptyValue="true"
     *    ),
     *    @OA\Parameter (
     *        name="filter[product_categories][1]",
     *        in="query",
     *        description="product_category 2 uuid",
     *        @OA\Schema(type="string"),
     *        allowEmptyValue="true"
     *    ),
     *    @OA\Parameter (
     *         name="filter[brands][0]",
     *         in="query",
     *         description="brand 1",
     *         @OA\Schema(type="string"),
     *         allowEmptyValue="true"
     *    ),
     *    @OA\Parameter (
     *         name="filter[brands][1]",
     *         in="query",
     *         @OA\Schema(type="string"),
     *         description="brand 2",
     *         allowEmptyValue="true"
     *    ),
     *    @OA\Parameter (
     *         name="filter[skin_types][0]",
     *         in="query",
     *         description="skin_type 1",
     *         @OA\Schema(type="string"),
     *         allowEmptyValue="true"
     *    ),
     *    @OA\Parameter (
     *         name="filter[skin_types][1]",
     *         in="query",
     *         @OA\Schema(type="string"),
     *         description="skin_type 2",
     *         allowEmptyValue="true"
     *    ),
     *    @OA\Parameter (
     *         name="filter[skin_concerns][0]",
     *         in="query",
     *         description="skin_concern 1",
     *         @OA\Schema(type="string"),
     *         allowEmptyValue="true"
     *    ),
     *    @OA\Parameter (
     *         name="filter[skin_concerns][1]",
     *         in="query",
     *         @OA\Schema(type="string"),
     *         description="skin_concern 2",
     *         allowEmptyValue="true"
     *    ),
     *    @OA\Parameter (
     *         name="filter[ingredients][0]",
     *         in="query",
     *         description="ingredient 1",
     *         @OA\Schema(type="string"),
     *         allowEmptyValue="true"
     *    ),
     *    @OA\Parameter (
     *         name="filter[ingredients][1]",
     *         in="query",
     *         @OA\Schema(type="string"),
     *         description="ingredient 2",
     *         allowEmptyValue="true"
     *    ),
     *    @OA\Parameter (
     *         name="filter[exclude_ingredients][0]",
     *         in="query",
     *         description="exclude_ingredient 1",
     *         @OA\Schema(type="string"),
     *         allowEmptyValue="true"
     *    ),
     *    @OA\Parameter (
     *         name="filter[exclude_ingredients][1]",
     *         in="query",
     *         @OA\Schema(type="string"),
     *         description="exclude_ingredient 2",
     *         allowEmptyValue="true"
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
            ->when(
                $request->has('sort'),
                fn (SearchParametersBuilder $builder) => $builder
                ->sort(
                    $request->getSortBy(),
                    $request->getSortDirection()
                )
            )
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
     *     path="/api/products/{slug}",
     *     @OA\Parameter(
     *         name="slug",
     *         in="path",
     *         required=true,
     *         description="Product slug",
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
