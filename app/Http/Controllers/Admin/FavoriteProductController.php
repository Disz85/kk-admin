<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreFavoriteProductRequest;
use App\Http\Requests\UpdateFavoriteProductRequest;
use App\Http\Resources\Admin\FavoriteProductCollection;
use App\Http\Resources\Admin\FavoriteProductResource;
use App\Models\FavoriteProduct;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use OpenApi\Annotations as OA;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class FavoriteProductController extends Controller
{
    /**
     * Store a newly created Favorite products group.
     *
     * @OA\Post (
     *     tags={"FavoriteProducts"},
     *     path="/admin/favorite-products",
     *     @OA\MediaType(
     *         mediaType="application/json"
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/x-www-form-urlencoded",
     *             @OA\Schema(
     *                 required={"name","product1_id","product2_id","product3_id"},
     *                 @OA\Property(
     *                     property="name",
     *                     type="string",
     *                     description="Name.",
     *                 ),
     *                 @OA\Property(
     *                     property="product1_id",
     *                     type="integer",
     *                     description="Product 1 ID.",
     *                 ),
     *                 @OA\Property(
     *                     property="product2_id",
     *                     type="integer",
     *                     description="Product 2 ID.",
     *                 ),
     *                 @OA\Property(
     *                     property="product3_id",
     *                     type="integer",
     *                     description="Product 3 ID.",
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
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Favorite products group created.",
     *         @OA\JsonContent(ref="#/components/schemas/FavoriteProduct"),
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Error in fields.",
     *         @OA\JsonContent()
     *     ),
     * )
     *
     * @param StoreFavoriteProductRequest $request
     * @return FavoriteProductResource
     */
    public function store(StoreFavoriteProductRequest $request): FavoriteProductResource
    {
        $favoriteProduct = FavoriteProduct::create($request->validated());
        $favoriteProduct->categories()->sync($request->categories);
        $favoriteProduct->refresh();

        return new FavoriteProductResource($favoriteProduct);
    }

    /**
     * Show a selected brand.
     * @OA\Get(
     *    tags={"FavoriteProducts"},
     *    path="/admin/favorite-products/{favorite_product}",
     *    @OA\Response(
     *      response="200",
     *      description="Display a favorite products group.",
     *      @OA\JsonContent(ref="#/components/schemas/FavoriteProduct"),
     *    ),
     *    @OA\Parameter(
     *        name="favorite_product",
     *        in="path",
     *        required=true,
     *        description="Favorite Product ID",
     *        @OA\Schema(type="integer"),
     *    ),
     * )
     * @param FavoriteProduct $favoriteProduct
     * @return FavoriteProductResource
     */
    public function show(FavoriteProduct $favoriteProduct): FavoriteProductResource
    {
        return new FavoriteProductResource($favoriteProduct->load(['categories','product1','product2','product3']));
    }

    /**
     * Update a Favorite products group.
     *
     * @OA\Put (
     *     tags={"FavoriteProducts"},
     *     path="/admin/favorite-products/{favorite_product}",
     *    @OA\Parameter(
     *      name="favorite_product",
     *      in="path",
     *      required=true,
     *      description="integer",
     *      @OA\Schema(type="string"),
     *    ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/x-www-form-urlencoded",
     *             @OA\Schema(
     *                 required={"name","product1_id","product2_id","product3_id"},
     *                 @OA\Property(
     *                     property="name",
     *                     type="string",
     *                     description="Name.",
     *                 ),
     *                 @OA\Property(
     *                     property="product1_id",
     *                     type="integer",
     *                     description="Product 1 ID.",
     *                 ),
     *                 @OA\Property(
     *                     property="product2_id",
     *                     type="integer",
     *                     description="Product 2 ID.",
     *                 ),
     *                 @OA\Property(
     *                     property="product3_id",
     *                     type="integer",
     *                     description="Product 3 ID.",
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
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Favorite products group updated.",
     *         @OA\JsonContent(ref="#/components/schemas/FavoriteProduct"),
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Input validation errors."
     *     ),
     * )
     *
     * @param UpdateFavoriteProductRequest $request
     * @param FavoriteProduct $favoriteProduct
     * @return FavoriteProductResource
     */
    public function update(UpdateFavoriteProductRequest $request, FavoriteProduct $favoriteProduct): FavoriteProductResource
    {
        $favoriteProduct->fill($request->validated())->save();
        $favoriteProduct->categories()->sync($request->categories);
        $favoriteProduct->refresh();

        return new FavoriteProductResource($favoriteProduct->load(['categories','product1','product2','product3']));
    }

    /**
     * Display a listing of the brands.
     *
     * @OA\Get(
     *    tags={"FavoriteProducts"},
     *    path="/admin/favorite-products",
     *    @OA\Response(
     *      response="200",
     *      description="Display a listing of favorite product groups.",
     *      @OA\JsonContent()
     *    ),
     *    @OA\Parameter(
     *      name="page",
     *      in="query",
     *      description="Page number",
     *      @OA\Schema(type="integer")
     *    ),
     *    @OA\Parameter(
     *      name="name",
     *      in="query",
     *      description="Filter by name",
     *      @OA\Schema(type="string")
     *    )
     * )
     *
     * @param Request $request
     * @return FavoriteProductCollection
     */
    public function index(Request $request): FavoriteProductCollection
    {
        return new FavoriteProductCollection(
            FavoriteProduct::query()
                ->when(
                    $request->has('name'),
                    fn (Builder $query) => $query->where('name', 'like', '%' . $request->get('name') . '%')
                )
                ->orderByDesc('updated_at')
                ->paginate($request->get('size', 20))
        );
    }

    /**
     * Remove a brand.
     *
     * @OA\Delete (
     *     tags={"FavoriteProducts"},
     *     path="/admin/favorite-products/{favorite_product}",
     *    @OA\Parameter(
     *      name="favorite_product",
     *      in="path",
     *      description="integer",
     *      @OA\Schema(type="string")
     *    ),
     *     @OA\RequestBody(
     *         required=false,
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Favorite product group deleted.",
     *         @OA\JsonContent(),
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Favorite product group not found.",
     *         @OA\JsonContent(),
     *     ),
     * )
     *
     * @param FavoriteProduct $favoriteProduct
     * @return JsonResponse
     * @throws Throwable
     */
    public function destroy(FavoriteProduct $favoriteProduct): JsonResponse
    {
        $favoriteProduct->deleteOrFail();

        return response()->json([], Response::HTTP_NO_CONTENT);
    }
}
