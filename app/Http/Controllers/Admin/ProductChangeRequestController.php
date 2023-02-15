<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProductChangeRequest;
use App\Http\Requests\UpdateProductChangeRequest;
use App\Http\Resources\Admin\ProductChangeRequestCollection;
use App\Http\Resources\Admin\ProductChangeRequestResource;
use App\Http\Resources\Admin\ProductResource;
use App\Mail\ProductChangeRequestRejectionMail;
use App\Models\Ingredient;
use App\Models\Product;
use App\Models\ProductChangeRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use OpenApi\Annotations as OA;
use Symfony\Component\HttpFoundation\Response;

class ProductChangeRequestController extends Controller
{
    /**
     * List of Product change request.
     *
     * @OA\Get(
     *    tags={"ProductChangeRequests"},
     *    path="/admin/product-change-requests",
     *    @OA\MediaType(mediaType="application/json"),
     *    @OA\Parameter(
     *      name="page",
     *      in="query",
     *      description="Page number",
     *      @OA\Schema(type="integer"),
     *    ),
     *    @OA\Response(
     *      response=200,
     *      description="Display a listing of prdouct change requests.",
     *      @OA\JsonContent(ref="#/components/schemas/ProductChangeRequest"),
     *    ),
     * )
     *
     * Display a list of the resource.
     *
     * @param Request $request
     * @return ProductChangeRequestCollection
     */
    public function index(Request $request): ProductChangeRequestCollection
    {
        return new ProductChangeRequestCollection(
            ProductChangeRequest::query()
                ->orderByDesc('created_at')
                ->paginate($request->get('size', 20))
        );
    }

    /**
     * Show a selected Product change request.
     * @OA\Get(
     *    tags={"ProductChangeRequests"},
     *    path="/admin/product-change-requests/{product_change_request}",
     *    @OA\Parameter(
     *      name="product_change_request",
     *      in="path",
     *      required=true,
     *      description="Product Change Request ID",
     *      @OA\Schema(type="integer"),
     *    ),
     *    @OA\Response(
     *      response=200,
     *      description="Display a selected Product Change Request.",
     *      @OA\JsonContent(ref="#/components/schemas/ProductChangeRequest"),
     *    ),
     *    @OA\Response(
     *      response=404,
     *      description="Product change request not found.",
     *      @OA\JsonContent(),
     *    )
     * )
     * @param ProductChangeRequest $productChangeRequest
     * @return ProductChangeRequestResource
     */
    public function show(ProductChangeRequest $productChangeRequest): ProductChangeRequestResource
    {
        return new ProductChangeRequestResource($productChangeRequest);
    }

    /**
     * Store a product change request.
     *
     * @OA\Post (
     *     tags={"ProductChangeRequests"},
     *     path="/admin/product-change-requests",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 required={"name", "image[id]", "is_active", "is_sponsored","is_18_plus","created_by", "brand_id", "price"},
     *                 @OA\Property(
     *                     property="name",
     *                     type="string",
     *                     description="name",
     *                 ),
     *                 @OA\Property(
     *                     property="product_id",
     *                     type="integer",
     *                     description="product_id",
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
     *                     type="integer",
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
     *                     example="0",
     *                 ),
     *                 @OA\Property(
     *                     property="is_sponsored",
     *                     type="integer",
     *                     description="1|0",
     *                     example="0",
     *                 ),
     *                 @OA\Property(
     *                     property="is_18_plus",
     *                     type="integer",
     *                     description="1|0",
     *                     example="0",
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
     *                 @OA\Property(
     *                     property="ingredients_by",
     *                     type="integer",
     *                     description="ingredients_by",
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
     *                 @OA\Property (
     *                     property="ingredients_new[0]",
     *                     type="integer",
     *                     description="new ingredient 1",
     *                 ),
     *                 @OA\Property (
     *                     property="ingredients_new[1]",
     *                     type="integer",
     *                     description="new ingredient 2",
     *                 ),
     *                 @OA\Property(
     *                     property="published_at",
     *                     type="datetime",
     *                      @OA\Schema(
     *                          type="string",
     *                          format ="date-time",
     *                      ),
     *                     description="Format: Y-m-d H:i:s",
     *                 ),
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Product change request created.",
     *         @OA\JsonContent(ref="#/components/schemas/ProductChangeRequest"),
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Error in fields.",
     *         @OA\JsonContent(),
     *     ),
     * )
     *
     * @param StoreProductChangeRequest $request
     * @return ProductChangeRequestResource
     */
    public function store(StoreProductChangeRequest $request): ProductChangeRequestResource
    {
        $productChangeRequest = ProductChangeRequest::create([
            'data' => $request->validated(),
            'product_id' => $request->product_id ?? null,
        ]);

        return new ProductChangeRequestResource($productChangeRequest);
    }

    /**
     * Approve a Product change request.
     *
     * @OA\Post (
     *     tags={"ProductChangeRequests"},
     *     path="/admin/product-change-requests/{product_change_request}/approve",
     *     @OA\MediaType(mediaType="application/json"),
     *    @OA\Parameter(
     *      name="product_change_request",
     *      in="path",
     *      required=true,
     *      description="integer",
     *      @OA\Schema(type="integer"),
     *    ),
     *     @OA\Response(
     *         response=200,
     *         description="Product change request approved",
     *         @OA\JsonContent()
     *     ),
     * )
     *
     * @param ProductChangeRequest $productChangeRequest
     * @return ProductResource
     */
    public function approve(ProductChangeRequest $productChangeRequest): ProductResource
    {
        $product = DB::transaction(function () use ($productChangeRequest) {
            $product = Product::updateOrCreate(['id' => $productChangeRequest->product->id ?? null], $productChangeRequest->data);
            foreach ($productChangeRequest->data['ingredients_new'] ?? [] as $newIngredientName) {
                $newIngredientIds[] = Ingredient::create(['name' => $newIngredientName])->id;
            }
            $product->ingredients()->sync(
                array_merge(
                    $newIngredientIds ?? [],
                    $productChangeRequest->data['ingredients'] ?? []
                )
            );
            $product->tags()->sync($productChangeRequest->data['tags'] ?? []);
            $product->categories()->sync($productChangeRequest->data['categories'] ?? []);
            $productChangeRequest->delete();

            return $product->refresh();
        });

        return new ProductResource($product->loadMissing(['tags', 'categories', 'ingredients']));
    }

    /**
     * Reject a Product change request.
     *
     * @OA\Post (
     *     tags={"ProductChangeRequests"},
     *     path="/admin/product-change-requests/{product_change_request}/reject",
     *     @OA\MediaType(mediaType="application/json"),
     *    @OA\Parameter(
     *      name="product_change_request",
     *      in="path",
     *      required=true,
     *      description="integer",
     *      @OA\Schema(type="integer"),
     *    ),
     *     @OA\Response(
     *         response=200,
     *         description="Product change request rejected",
     *         @OA\JsonContent()
     *     ),
     * )
     *
     * @param ProductChangeRequest $productChangeRequest
     * @return JsonResponse
     */
    public function reject(ProductChangeRequest $productChangeRequest): JsonResponse
    {
        $user = User::findOrFail($productChangeRequest->data['created_by']);

        try {
            Mail::send(new ProductChangeRequestRejectionMail($productChangeRequest, $user));
        } catch (\Exception $e) {
            return response()->json(['error' => 'Email küldés sikertelen'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
        $productChangeRequest->delete();

        return response()->json([], Response::HTTP_OK);
    }

    /**
     * Update a product change request.
     *
     * @OA\Put (
     *    tags={"ProductChangeRequests"},
     *    path="/admin/product-change-requests/{product_change_request}",
     *    @OA\Parameter(
     *      name="product_change_request",
     *      in="path",
     *      description="integer",
     *      @OA\Schema(type="string"),
     *    ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 required={"name", "image[id]", "is_active", "is_sponsored","is_18_plus","created_by", "brand_id", "price"},
     *                 @OA\Property(
     *                     property="name",
     *                     type="string",
     *                     description="name",
     *                 ),
     *                 @OA\Property(
     *                     property="product_id",
     *                     type="integer",
     *                     description="product_id",
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
     *                     type="integer",
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
     *                     example="0",
     *                 ),
     *                 @OA\Property(
     *                     property="is_sponsored",
     *                     type="integer",
     *                     description="1|0",
     *                     example="0",
     *                 ),
     *                 @OA\Property(
     *                     property="is_18_plus",
     *                     type="integer",
     *                     description="1|0",
     *                     example="0",
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
     *                 @OA\Property(
     *                     property="ingredients_by",
     *                     type="integer",
     *                     description="ingredients_by",
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
     *                 @OA\Property (
     *                     property="ingredients_new[0]",
     *                     type="integer",
     *                     description="new ingredient 1 name",
     *                 ),
     *                 @OA\Property (
     *                     property="ingredients_new[1]",
     *                     type="integer",
     *                     description="new ingredient 2 name",
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
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Product change request created.",
     *         @OA\JsonContent(ref="#/components/schemas/ProductChangeRequest"),
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Error in fields.",
     *         @OA\JsonContent(),
     *     ),
     * )
     *
     * @param UpdateProductChangeRequest $request
     * @param ProductChangeRequest $productChangeRequest
     * @return ProductChangeRequestResource
     */
    public function update(UpdateProductChangeRequest $request, ProductChangeRequest $productChangeRequest): ProductChangeRequestResource
    {
        $productChangeRequest->data = $request->validated();
        $productChangeRequest->product_id = $request->product_id ?? null;
        $productChangeRequest->save();

        return new ProductChangeRequestResource($productChangeRequest);
    }
}
