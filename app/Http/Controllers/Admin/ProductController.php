<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Http\Resources\Admin\ProductCollection;
use App\Http\Resources\Admin\ProductResource;
use App\Models\Product;
use App\RequestMappers\ProductRequestMapper;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use OpenApi\Annotations as OA;
use Throwable;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @OA\Get(
     *    tags={"Products"},
     *    path="/admin/products",
     *    security={{"bearer":{}}},
     *    @OA\Parameter(
     *        name="page",
     *        in="query",
     *        description="Page number",
     *        @OA\Schema(type="integer"),
     *        allowEmptyValue="true",
     *    ),
     *    @OA\Parameter(
     *      name="name",
     *      in="query",
     *      description="Filter by name",
     *      @OA\Schema(type="string"),
     *    ),
     *    @OA\Response(
     *        response=200,
     *        description="Display a listing of products.",
     *        @OA\JsonContent(ref="#/components/schemas/Product"),
     *    ),
     *    @OA\Response(
     *        response=404,
     *        description="No products.",
     *        @OA\JsonContent(),
     *    )
     * )
     *
     * @param Request $request
     * @return ProductCollection
     */
    public function index(Request $request): ProductCollection
    {
        return new ProductCollection(
            Product::query()
                ->when(
                    $request->has('name'),
                    fn (Builder $query) => $query->where('name', 'like', '%' . $request->get('name') . '%')
                )
                ->orderByDesc('published_at')
                ->paginate($request->get('size', 20))
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @OA\Post (
     *     tags={"Products"},
     *     path="/admin/products",
     *     security={{"bearer":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 required={"name", "image[id]", "is_active", "is_sponsored", "is_18_plus", "brand_id", "price"},
     *                 @OA\Property(
     *                     property="name",
     *                     type="string",
     *                     description="name",
     *                 ),
     *                 @OA\Property(
     *                     property="canonical_name",
     *                     type="string",
     *                     description="canonical_name",
     *                 ),
     *                 @OA\Property(
     *                     property="description",
     *                     type="string",
     *                     description="Desciption",
     *                 ),
     *                 @OA\Property(
     *                     property="price",
     *                     type="string",
     *                     description="price",
     *                     example="1",
     *                 ),
     *                 @OA\Property(
     *                     property="size",
     *                     type="string",
     *                     description="size",
     *                 ),
     *                 @OA\Property(
     *                     property="brand_id",
     *                     type="integer",
     *                     description="brand_id",
     *                 ),
     *                 @OA\Property(
     *                     property="is_active",
     *                     type="integer",
     *                     description="1|0",
     *                 ),
     *                 @OA\Property(
     *                     property="is_sponsored",
     *                     type="integer",
     *                     description="1|0",
     *                 ),
     *                 @OA\Property(
     *                     property="is_18_plus",
     *                     type="integer",
     *                     description="1|0",
     *                 ),
     *                 @OA\Property(
     *                     property="ingredients_by",
     *                     type="integer",
     *                     description="ingredients_by",
     *                 ),
     *                 @OA\Property(
     *                     property="created_by",
     *                     type="integer",
     *                     description="created_by",
     *                 ),
     *                 @OA\Property (
     *                     property="image[id]",
     *                     type="integer",
     *                     description="Image ID",
     *                 ),
     *                 @OA\Property (
     *                     property="categories[0]",
     *                     type="integer",
     *                     description="category 1 id",
     *                 ),
     *                 @OA\Property (
     *                     property="categories[1]",
     *                     type="integer",
     *                     description="category 2 id",
     *                 ),
     *                 @OA\Property (
     *                     property="tags[0]",
     *                     type="integer",
     *                     description="tag 1 id",
     *                 ),
     *                 @OA\Property (
     *                     property="tags[1]",
     *                     type="integer",
     *                     description="tag 2 id",
     *                 ),
     *                 @OA\Property (
     *                     property="ingredients[0]",
     *                     type="integer",
     *                     description="ingredient 1 id",
     *                 ),
     *                 @OA\Property (
     *                     property="ingredients[1]",
     *                     type="integer",
     *                     description="ingredient 2 id",
     *                 ),
     *                 @OA\Property(
     *                     property="published_at",
     *                     type="datetime",
     *                  @OA\Schema(
     *                      type="string",
     *                      format ="date-time",
     *                  ),
     *                     description="Format: Y-m-d H:i:s",
     *                 ),
     *             ),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Product created.",
     *         @OA\JsonContent(ref="#/components/schemas/Product")
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Error in fields."
     *     ),
     * )
     *
     * @param StoreProductRequest $request
     * @param Product $product
     * @param ProductRequestMapper $productRequestMapper
     * @return ProductResource
     */
    public function store(StoreProductRequest $request, Product $product, ProductRequestMapper $productRequestMapper): ProductResource
    {
        return new ProductResource($productRequestMapper->map($product, $request->validated()));
    }

    /**
     * Display the specified resource.
     *
     * @OA\Get(
     *    tags={"Products"},
     *    path="/admin/products/{product}",
     *    security={{"bearer":{}}},
     *    @OA\MediaType(
     *      mediaType="application/json"
     *    ),
     *    @OA\Parameter(
     *        name="product",
     *        in="path",
     *        required=true,
     *        description="product ID",
     *        @OA\Schema(
     *            type="integer"
     *        ),
     *    ),
     *    @OA\Response(
     *      response=200,
     *      description="Display a selected Product.",
     *      @OA\JsonContent(ref="#/components/schemas/Product")
     *    ),
     *    @OA\Response(
     *        response=404,
     *        description="Product not found.",
     *        @OA\JsonContent()
     *    ),
     * )
     *
     * @param Product $product
     * @return ProductResource
     */
    public function show(Product $product): ProductResource
    {
        $product->load(['tags','categories','ingredients']);

        return new ProductResource($product);
    }

    /**
     * Update the specified resource in storage.
     *
     * @OA\Put (
     *     tags={"Products"},
     *     path="/admin/products/{product}",
     *     security={{"bearer":{}}},
     *    @OA\Parameter(
     *      name="product",
     *      in="path",
     *      required=true,
     *      description="integer",
     *      @OA\Schema(type="string"),
     *    ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 required={"name", "image[id]", "is_active", "is_sponsored", "is_18_plus", "brand_id", "price"},
     *                 @OA\Property(
     *                     property="name",
     *                     type="string",
     *                     description="name",
     *                 ),
     *                 @OA\Property(
     *                     property="canonical_name",
     *                     type="string",
     *                     description="canonical_name",
     *                 ),
     *                 @OA\Property(
     *                     property="description",
     *                     type="string",
     *                     description="Desciption",
     *                 ),
     *                 @OA\Property(
     *                     property="price",
     *                     type="string",
     *                     description="price",
     *                     example="1",
     *                 ),
     *                 @OA\Property(
     *                     property="size",
     *                     type="string",
     *                     description="size",
     *                 ),
     *                 @OA\Property(
     *                     property="brand_id",
     *                     type="integer",
     *                     description="brand_id",
     *                 ),
     *                 @OA\Property(
     *                     property="is_active",
     *                     type="integer",
     *                     description="1|0",
     *                 ),
     *                 @OA\Property(
     *                     property="is_sponsored",
     *                     type="integer",
     *                     description="1|0",
     *                 ),
     *                 @OA\Property(
     *                     property="is_18_plus",
     *                     type="integer",
     *                     description="1|0",
     *                 ),
     *                 @OA\Property(
     *                     property="ingredients_by",
     *                     type="integer",
     *                     description="ingredients_by",
     *                 ),
     *                 @OA\Property(
     *                     property="updated_by",
     *                     type="integer",
     *                     description="updated_by",
     *                 ),
     *                 @OA\Property (
     *                     property="image[id]",
     *                     type="integer",
     *                     description="Image ID",
     *                 ),
     *                 @OA\Property (
     *                     property="categories[0]",
     *                     type="integer",
     *                     description="category 1 id",
     *                 ),
     *                 @OA\Property (
     *                     property="categories[1]",
     *                     type="integer",
     *                     description="category 2 id",
     *                 ),
     *                 @OA\Property (
     *                     property="tags[0]",
     *                     type="integer",
     *                     description="tag 1 id",
     *                 ),
     *                 @OA\Property (
     *                     property="tags[1]",
     *                     type="integer",
     *                     description="tag 2 id",
     *                 ),
     *                 @OA\Property (
     *                     property="ingredients[0]",
     *                     type="integer",
     *                     description="ingredient 1 id",
     *                 ),
     *                 @OA\Property (
     *                     property="ingredients[1]",
     *                     type="integer",
     *                     description="ingredient 2 id",
     *                 ),
     *                 @OA\Property(
     *                     property="published_at",
     *                     type="datetime",
     *                  @OA\Schema(
     *                      type="string",
     *                      format ="date-time",
     *                  ),
     *                     description="Format: Y-m-d H:i:s",
     *                 ),
     *             ),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Product updated.",
     *         @OA\JsonContent(ref="#/components/schemas/Product")
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Error in fields."
     *     ),
     * )
     *
     * @param UpdateProductRequest $request
     * @param Product $product
     * @param ProductRequestMapper $productRequestMapper
     * @return ProductResource
     */
    public function update(UpdateProductRequest $request, Product $product, ProductRequestMapper $productRequestMapper): ProductResource
    {
        return new ProductResource($productRequestMapper->map($product, $request->validated()));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @OA\Delete (
     *     tags={"Products"},
     *     path="/admin/product/{product}",
     *     security={{"bearer":{}}},
     *     @OA\RequestBody(
     *         required=false,
     *         @OA\MediaType(mediaType="application/x-www-form-urlencoded"),
     *     ),
     *    @OA\Parameter(
     *      name="product",
     *      in="path",
     *      description="Product ID",
     *      @OA\Schema(type="integer"),
     *    ),
     *    @OA\Response(
     *      response=204,
     *      description="Product deleted.",
     *      @OA\JsonContent(),
     *    ),
     *    @OA\Response(
     *      response=404,
     *      description="Product not found.",
     *      @OA\JsonContent(),
     *    )
     * )
     *
     * @param Product $product
     * @return JsonResponse
     * @throws Throwable
     */
    public function destroy(Product $product): JsonResponse
    {
        $product->deleteOrFail();

        return response()->json([], Response::HTTP_NO_CONTENT);
    }
}
